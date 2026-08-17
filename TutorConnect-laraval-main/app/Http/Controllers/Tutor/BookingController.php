<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Mail\BookingCancelled;
use App\Mail\BookingConfirmed;
use App\Mail\SessionCompleted;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $tutorId = Auth::id();
        $tab = $request->get('tab', 'all');

        $query = Booking::where('tutor_id', $tutorId)
            ->with(['student.studentProfile', 'subject', 'payment', 'review']);

        if ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'confirmed') {
            $query->where('status', 'confirmed');
        } elseif ($tab === 'completed') {
            $query->where('status', 'completed');
        } elseif ($tab === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $bookings = $query->orderBy('booking_date', 'desc')->orderBy('start_time', 'desc')->paginate(10)->withQueryString();

        $counts = [
            'all' => Booking::where('tutor_id', $tutorId)->count(),
            'pending' => Booking::where('tutor_id', $tutorId)->where('status', 'pending')->count(),
            'confirmed' => Booking::where('tutor_id', $tutorId)->where('status', 'confirmed')->count(),
            'completed' => Booking::where('tutor_id', $tutorId)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('tutor_id', $tutorId)->where('status', 'cancelled')->count(),
        ];

        return view('tutor.bookings.index', compact('bookings', 'counts', 'tab'));
    }

    public function confirm($id)
    {
        $booking = Booking::where('tutor_id', Auth::id())->findOrFail($id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be confirmed.');
        }

        $booking->update(['status' => 'confirmed']);

        try {
            Mail::to($booking->student->email)->send(new BookingConfirmed($booking));
        } catch (\Exception $e) {
        }

        return back()->with('success', 'Booking has been confirmed. Student has been notified.');
    }

    public function complete($id)
    {
        $booking = Booking::where('tutor_id', Auth::id())->findOrFail($id);

        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed bookings can be marked completed.');
        }

        $booking->update(['status' => 'completed']);

        try {
            Mail::to($booking->student->email)->send(new SessionCompleted($booking));
        } catch (\Exception $e) {
        }

        return back()->with('success', 'Session marked as completed! Student invited to leave a review.');
    }

    public function cancel(Request $request, $id)
    {
        $booking = Booking::where('tutor_id', Auth::id())->findOrFail($id);

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:500'],
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_by' => 'tutor',
        ]);

        try {
            Mail::to($booking->student->email)->send(new BookingCancelled($booking, $request->cancellation_reason));
        } catch (\Exception $e) {
        }

        return back()->with('success', 'Booking declined/cancelled.');
    }
}
