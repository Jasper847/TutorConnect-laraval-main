<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tutor = Auth::user();
        $tutor->load('tutorProfile.subjects');

        $pendingBookings = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'pending')
            ->with(['student.studentProfile', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get();

        $upcomingSessions = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'confirmed')
            ->where('booking_date', '>=', now()->toDateString())
            ->with(['student', 'subject'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        $stats = [
            'total_students' => Booking::where('tutor_id', $tutor->id)->distinct('student_id')->count('student_id'),
            'total_earnings' => Payment::whereHas('booking', function ($q) use ($tutor) {
                $q->where('tutor_id', $tutor->id);
            })->where('status', 'succeeded')->sum('amount'),
            'completed_sessions' => Booking::where('tutor_id', $tutor->id)->where('status', 'completed')->count(),
            'rating' => $tutor->tutorProfile->rating_cache ?? 0.00,
            'reviews_count' => $tutor->tutorProfile->reviews_count ?? 0,
            'unread_messages' => Message::where('receiver_id', $tutor->id)->where('is_read', false)->count(),
        ];

        $recentReviews = Review::where('tutor_id', $tutor->id)
            ->with('student')
            ->latest()
            ->take(3)
            ->get();

        return view('tutor.dashboard', compact('tutor', 'pendingBookings', 'upcomingSessions', 'stats', 'recentReviews'));
    }
}
