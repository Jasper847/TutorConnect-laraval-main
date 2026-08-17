<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;

class TutorDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::orderBy('name')->get();

        $query = TutorProfile::with(['user', 'subjects', 'availabilities'])
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            });

        // Search by name, headline, bio
        if ($request->filled('q')) {
            $searchTerm = '%' . $request->q . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('headline', 'like', $searchTerm)
                  ->orWhere('bio', 'like', $searchTerm)
                  ->orWhereHas('user', function ($userQ) use ($searchTerm) {
                      $userQ->where('name', 'like', $searchTerm)
                            ->orWhere('city', 'like', $searchTerm);
                  });
            });
        }

        // Filter by Subject
        if ($request->filled('subject')) {
            $query->whereHas('subjects', function ($q) use ($request) {
                $q->where('slug', $request->subject)->orWhere('subjects.id', $request->subject);
            });
        }

        // Filter by Price min/max
        if ($request->filled('min_price')) {
            $query->where('hourly_rate', '>=', (float)$request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('hourly_rate', '<=', (float)$request->max_price);
        }

        // Filter by Teaching Mode
        if ($request->filled('mode') && $request->mode !== 'all') {
            $query->where(function ($q) use ($request) {
                $q->where('teaching_mode', $request->mode)->orWhere('teaching_mode', 'both');
            });
        }

        // Filter by Rating
        if ($request->filled('rating')) {
            $query->where('rating_cache', '>=', (float)$request->rating);
        }

        // Filter by Verified only
        if ($request->boolean('verified_only')) {
            $query->where('is_verified', true);
        }

        // Sorting
        $sort = $request->get('sort', 'recommended');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('hourly_rate', 'asc');
                break;
            case 'price_high':
                $query->orderBy('hourly_rate', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating_cache', 'desc');
                break;
            case 'experience':
                $query->orderBy('experience_years', 'desc');
                break;
            default:
                $query->orderBy('is_verified', 'desc')->orderBy('rating_cache', 'desc');
                break;
        }

        $tutors = $query->paginate(9)->withQueryString();

        return view('tutors.index', compact('tutors', 'subjects'));
    }

    public function show($id)
    {
        $tutor = User::where('role', 'tutor')
            ->where('is_active', true)
            ->with(['tutorProfile.subjects', 'tutorProfile.availabilities'])
            ->findOrFail($id);

        $reviews = Review::where('tutor_id', $id)
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        $relatedTutors = TutorProfile::with('user')
            ->where('user_id', '!=', $id)
            ->whereHas('subjects', function ($q) use ($tutor) {
                $subjectIds = $tutor->tutorProfile->subjects->pluck('id');
                $q->whereIn('subjects.id', $subjectIds);
            })
            ->take(3)
            ->get();

        return view('tutors.show', compact('tutor', 'reviews', 'relatedTutors'));
    }
}
