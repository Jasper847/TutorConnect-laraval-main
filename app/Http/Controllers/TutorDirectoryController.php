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

        $query = TutorProfile::with(['user', 'availabilities'])
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            });

        // Keyword search (name, headline, bio, city, or subject)
        if ($request->filled('q')) {
            $searchTerm = '%' . $request->q . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('headline', 'like', $searchTerm)
                  ->orWhere('bio', 'like', $searchTerm)
                  ->orWhere('subjects', 'like', $searchTerm)
                  ->orWhere('education', 'like', $searchTerm)
                  ->orWhere('location', 'like', $searchTerm)
                  ->orWhereHas('user', function ($userQ) use ($searchTerm) {
                      $userQ->where('name', 'like', $searchTerm)
                            ->orWhere('city', 'like', $searchTerm);
                  });
            });
        }

        // Filter by Subject
        if ($request->filled('subject')) {
            $subjParam = $request->subject;
            $subjectObj = Subject::where('slug', $subjParam)->orWhere('name', $subjParam)->first();
            $subjName = $subjectObj ? $subjectObj->name : $subjParam;
            
            $query->where(function ($q) use ($subjName, $subjParam) {
                $q->where('subjects', 'like', '%' . $subjName . '%')
                  ->orWhere('subjects', 'like', '%' . $subjParam . '%');
            });
        }

        // Filter by Price min/max
        if ($request->filled('min_price')) {
            $query->where('hourly_rate', '>=', (float)$request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('hourly_rate', '<=', (float)$request->max_price);
        }

        // Filter by Rating
        if ($request->filled('rating')) {
            $query->where('avg_rating', '>=', (float)$request->rating);
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
                $query->orderBy('avg_rating', 'desc');
                break;
            case 'experience':
                $query->orderBy('experience_years', 'desc');
                break;
            default:
                $query->orderBy('is_verified', 'desc')->orderBy('avg_rating', 'desc');
                break;
        }

        $tutors = $query->paginate(9)->withQueryString();

        return view('tutors.index', compact('tutors', 'subjects'));
    }

    public function show($id)
    {
        $tutor = User::where('role', 'tutor')
            ->where('is_active', true)
            ->with(['tutorProfile.availabilities'])
            ->findOrFail($id);

        $reviews = Review::where('tutor_id', $id)
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        $relatedTutors = TutorProfile::with('user')
            ->where('user_id', '!=', $id)
            ->where('is_available', true)
            ->take(3)
            ->get();

        return view('tutors.show', compact('tutor', 'reviews', 'relatedTutors'));
    }
}
