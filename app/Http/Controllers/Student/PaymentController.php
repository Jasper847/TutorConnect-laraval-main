<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmed;
use App\Mail\PaymentSuccessMail;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Show Stripe Checkout page for a booking.
     */
    public function checkout($bookingId): View|RedirectResponse
    {
        $booking = Booking::where('student_id', Auth::id())
            ->with(['tutor.tutorProfile'])
            ->findOrFail($bookingId);

        if ($booking->status === 'confirmed') {
            return redirect()->route('student.bookings.show', $booking->id)
                ->with('info', 'This session has already been paid and confirmed.');
        }

        // Amount in PKR & USD equivalent (approx PKR 280 = $1 USD)
        $amountPkr = (float) $booking->total_amount;
        $amountUsd = round($amountPkr / 280, 2);
        if ($amountUsd < 1.00) {
            $amountUsd = 1.00;
        }

        $stripeKey = env('STRIPE_KEY', 'pk_test_demo_key_tutorconnect');

        return view('student.payment.checkout', compact('booking', 'amountPkr', 'amountUsd', 'stripeKey'));
    }

    /**
     * Process Stripe Payment in Demo Sandbox mode.
     */
    public function processPayment(Request $request, $bookingId): RedirectResponse
    {
        $booking = Booking::where('student_id', Auth::id())
            ->with(['tutor', 'student'])
            ->findOrFail($bookingId);

        $request->validate([
            'card_number' => ['required', 'string'],
            'expiry' => ['required', 'string'],
            'cvv' => ['required', 'string'],
        ]);

        $amountPkr = (float) $booking->total_amount;
        $amountUsd = round($amountPkr / 280, 2);
        if ($amountUsd < 1.00) $amountUsd = 1.00;

        $paymentIntentId = 'pi_test_' . Str::random(24);

        // Attempt server-side Stripe SDK call if stripe-php is loaded and secret is set
        if (class_exists('\Stripe\StripeClient')) {
            $stripeSecret = env('STRIPE_SECRET');
            if ($stripeSecret && !str_starts_with($stripeSecret, 'sk_test_your_')) {
                try {
                    $stripe = new \Stripe\StripeClient($stripeSecret);
                    $intent = $stripe->paymentIntents->create([
                        'amount' => (int) ($amountUsd * 100), // in cents
                        'currency' => env('STRIPE_CURRENCY', 'usd'),
                        'payment_method_types' => ['card'],
                        'description' => 'TutorConnect Demo: Session #' . $booking->booking_code . ' with ' . $booking->tutor->name,
                    ]);
                    $paymentIntentId = $intent->id;
                } catch (\Exception $e) {
                    Log::warning('Stripe API sandbox warning: ' . $e->getMessage() . ' - Falling back to local simulated sandbox intent.');
                }
            }
        }

        // Save Payment record in Database
        $payment = Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'stripe_payment_intent_id' => $paymentIntentId,
                'amount' => $amountUsd,
                'currency' => env('STRIPE_CURRENCY', 'usd'),
                'status' => 'paid',
                'is_demo' => true,
            ]
        );

        // Update Booking Status to Confirmed
        $booking->update(['status' => 'confirmed']);

        // Dispatch Payment Confirmation Email
        try {
            Mail::to($booking->student->email)->send(new PaymentSuccessMail($booking, $payment));
            Mail::to($booking->tutor->email)->send(new BookingConfirmed($booking));
        } catch (\Exception $e) {
            Log::info('Mail dispatch notice: ' . $e->getMessage());
        }

        return redirect()->route('student.payment.success', $booking->id);
    }

    /**
     * Payment Success Page with receipt.
     */
    public function paymentSuccess($bookingId): View
    {
        $booking = Booking::where('student_id', Auth::id())
            ->with(['tutor.tutorProfile', 'payment'])
            ->findOrFail($bookingId);

        $payment = $booking->payment;

        return view('student.payment.success', compact('booking', 'payment'));
    }

    /**
     * Payment Cancelled Page.
     */
    public function paymentCancel($bookingId): View
    {
        $booking = Booking::where('student_id', Auth::id())
            ->with('tutor')
            ->findOrFail($bookingId);

        return view('student.payment.cancel', compact('booking'));
    }
}
