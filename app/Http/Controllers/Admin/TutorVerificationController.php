<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TutorProfile;
use Illuminate\Http\Request;

class TutorVerificationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = TutorProfile::with(['user', 'subjects']);

        if ($status === 'pending') {
            $query->where('is_verified', false);
        } elseif ($status === 'verified') {
            $query->where('is_verified', true);
        }

        $tutors = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $counts = [
            'pending' => TutorProfile::where('is_verified', false)->count(),
            'verified' => TutorProfile::where('is_verified', true)->count(),
        ];

        return view('admin.verifications.index', compact('tutors', 'counts', 'status'));
    }

    public function verify($id)
    {
        $tutorProfile = TutorProfile::findOrFail($id);
        $tutorProfile->update(['is_verified' => true]);
        return back()->with('success', "Tutor {$tutorProfile->user->name} has been verified and granted badge.");
    }

    public function reject($id)
    {
        $tutorProfile = TutorProfile::findOrFail($id);
        $tutorProfile->update(['is_verified' => false]);
        return back()->with('info', "Tutor {$tutorProfile->user->name} verification status set to unverified.");
    }
}
