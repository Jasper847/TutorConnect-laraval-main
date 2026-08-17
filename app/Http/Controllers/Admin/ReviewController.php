<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['student', 'tutor.tutorProfile', 'booking.subject']);

        if ($request->filled('rating')) {
            $query->where('rating', (int)$request->rating);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete(); // Automatically triggers Eloquent event to recalculate tutor rating_cache
        return back()->with('success', 'Review deleted and tutor rating recalculated.');
    }
}
