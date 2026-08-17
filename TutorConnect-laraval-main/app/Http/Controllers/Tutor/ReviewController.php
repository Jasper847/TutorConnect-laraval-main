<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $tutorId = Auth::id();

        $reviews = Review::where('tutor_id', $tutorId)
            ->with(['student.studentProfile', 'booking.subject'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $ratingDistribution = [
            5 => Review::where('tutor_id', $tutorId)->where('rating', 5)->count(),
            4 => Review::where('tutor_id', $tutorId)->where('rating', 4)->count(),
            3 => Review::where('tutor_id', $tutorId)->where('rating', 3)->count(),
            2 => Review::where('tutor_id', $tutorId)->where('rating', 2)->count(),
            1 => Review::where('tutor_id', $tutorId)->where('rating', 1)->count(),
        ];

        $totalReviews = array_sum($ratingDistribution);

        return view('tutor.reviews.index', compact('reviews', 'ratingDistribution', 'totalReviews'));
    }
}
