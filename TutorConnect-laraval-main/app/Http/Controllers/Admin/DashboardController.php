<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_tutors' => User::where('role', 'tutor')->count(),
            'pending_verifications' => TutorProfile::where('is_verified', false)->count(),
            'total_bookings' => Booking::count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_revenue' => Payment::where('status', 'succeeded')->sum('amount'),
            'total_reviews' => Review::count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentBookings = Booking::with(['student', 'tutor.tutorProfile', 'subject'])->latest()->take(5)->get();
        $pendingTutors = TutorProfile::with('user')->where('is_verified', false)->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentBookings', 'pendingTutors'));
    }
}
