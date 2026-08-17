<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmed;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function checkout($bookingId)
    {
        $booking = Booking::where('student_id', Auth::id())
            ->with(['tutor.tutorProfile', 'subject'])
            ->findOrFail($bookingId);

        if ($booking->status === 'confirmed') {
            return redirect()->route('student.bookings.show', $booking->id)->with('info', 'This booking has already been paid and confirmed.');
        }

        return view('student.payment.sandbox-checkout', compact('booking'));
    }

    public function processSandbox(Request $request, $bookingId)
    {
        $booking = Booking::where('student_id', Auth::id())
            ->with('tutor')
            ->findOrFail($bookingId);

        $request->validate([
            'card_number' => ['required', 'string'],
            'expiry' => ['required', 'string'],
            'cvv' => ['required', 'string'],
        ]);

        // Record Sandbox Payment
        $payment = Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'user_id' => Auth::id(),
                'stripe_session_id' => 'cs_test_' . Str::random(24),
                'stripe_payment_intent_id' => 'pi_test_' . Str::random(24),
                'amount' => $booking->total_amount,
                'currency' => 'usd',
                'status' => 'succeeded',
                'payment_method' => 'stripe_card_sandbox',
                'is_sandbox' => true,
            ]
        );

        // Update Booking to Confirmed
        $booking->update([
            'status' => 'confirmed',
        ]);

        // Send Confirmation Email
        try {
            Mail::to($booking->student->email)->send(new BookingConfirmed($booking));
            Mail::to($booking->tutor->email)->send(new BookingConfirmed($booking));
        } catch (\Exception $e) {
        }

        return redirect()->route('student.payment.success', $booking->id)->with('success', 'Sandbox Payment Successful! Your session is confirmed.');
    }

    public function success($bookingId)
    {
        $booking = Booking::where('student_id', Auth::id())
            ->with(['tutor.tutorProfile', 'subject', 'payment'])
            ->findOrFail($bookingId);

        return view('student.payment.success', compact('booking'));
    }

    public function cancel($bookingId)
    {
        return redirect()->route('student.bookings.index')->with('info', 'Payment was not completed. Your booking remains pending.');
    }
}
