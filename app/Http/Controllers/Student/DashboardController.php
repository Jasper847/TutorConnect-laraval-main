<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Message;
use App\Models\TutorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $student->load('studentProfile');

        $upcomingBookings = Booking::where('student_id', $student->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('booking_date', '>=', now()->toDateString())
            ->with(['tutor.tutorProfile', 'subject'])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        $stats = [
            'total_bookings' => Booking::where('student_id', $student->id)->count(),
            'upcoming_sessions' => Booking::where('student_id', $student->id)
                ->where('status', 'confirmed')
                ->where('booking_date', '>=', now()->toDateString())
                ->count(),
            'completed_sessions' => Booking::where('student_id', $student->id)
                ->where('status', 'completed')
                ->count(),
            'unread_messages' => Message::where('receiver_id', $student->id)
                ->where('is_read', false)
                ->count(),
        ];

        $recommendedTutors = TutorProfile::with(['user', 'subjects'])
            ->where('is_verified', true)
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            })
            ->orderBy('rating_cache', 'desc')
            ->take(4)
            ->get();

        $recentMessages = Message::where('receiver_id', $student->id)
            ->orWhere('sender_id', $student->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->take(5)
            ->get();

        return view('student.dashboard', compact('student', 'upcomingBookings', 'stats', 'recommendedTutors', 'recentMessages'));
    }
}
