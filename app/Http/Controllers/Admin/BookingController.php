<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['student', 'tutor.tutorProfile', 'subject', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($q) use ($term) {
                $q->where('booking_code', 'like', $term)
                  ->orWhereHas('student', fn($sq) => $sq->where('name', 'like', $term))
                  ->orWhereHas('tutor', fn($tq) => $tq->where('name', 'like', $term));
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['student.studentProfile', 'tutor.tutorProfile', 'subject', 'payment', 'review'])
            ->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:500'],
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_by' => 'admin',
        ]);

        return back()->with('success', 'Booking cancelled by Administrator.');
    }
}
