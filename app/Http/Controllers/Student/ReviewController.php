<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create($bookingId)
    {
        $booking = Booking::where('student_id', Auth::id())
            ->with(['tutor.tutorProfile', 'subject'])
            ->findOrFail($bookingId);

        if (!$booking->isCompleted()) {
            return redirect()->route('student.bookings.show', $booking->id)
                ->with('error', 'You can only review a tutor after completing the session.');
        }

        if ($booking->review()->exists()) {
            return redirect()->route('student.bookings.show', $booking->id)
                ->with('info', 'You have already reviewed this tutoring session.');
        }

        return view('student.reviews.create', compact('booking'));
    }

    public function store(Request $request, $bookingId)
    {
        $booking = Booking::where('student_id', Auth::id())->findOrFail($bookingId);

        if (!$booking->isCompleted()) {
            return back()->with('error', 'Session must be marked as completed before leaving a review.');
        }

        if ($booking->review()->exists()) {
            return back()->with('error', 'Review already submitted for this booking.');
        }

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'student_id' => Auth::id(),
            'tutor_id' => $booking->tutor_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('student.bookings.show', $booking->id)
            ->with('success', 'Thank you! Your review has been published and rating calculated.');
    }
}
