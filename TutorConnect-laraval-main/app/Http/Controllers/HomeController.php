<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\Review;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $subjects = Subject::withCount('tutors')->orderBy('tutors_count', 'desc')->take(8)->get();
        
        $featuredTutors = TutorProfile::with(['user', 'subjects'])
            ->where('is_verified', true)
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            })
            ->orderBy('rating_cache', 'desc')
            ->take(6)
            ->get();

        $latestReviews = Review::with(['student', 'tutor.tutorProfile'])
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $stats = [
            'total_tutors' => User::where('role', 'tutor')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_sessions' => Booking::where('status', 'completed')->count(),
            'avg_rating' => round(Review::avg('rating') ?? 4.9, 1),
        ];

        return view('home', compact('subjects', 'featuredTutors', 'latestReviews', 'stats'));
    }

    public function about()
    {
        return view('about');
    }
}
