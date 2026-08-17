<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\StudyMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyMaterialController extends Controller
{
    public function index()
    {
        $studentId = Auth::id();

        // Get tutors student has active or completed bookings with
        $tutorIds = Booking::where('student_id', $studentId)
            ->whereIn('status', ['confirmed', 'completed'])
            ->pluck('tutor_id')
            ->unique();

        $materials = StudyMaterial::whereIn('tutor_id', $tutorIds)
            ->with(['tutor.tutorProfile', 'subject'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('student.materials.index', compact('materials'));
    }

    public function download($id)
    {
        $material = StudyMaterial::findOrFail($id);
        $studentId = Auth::id();

        // Verify student has booked with this tutor
        $hasBooking = Booking::where('student_id', $studentId)
            ->where('tutor_id', $material->tutor_id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->exists();

        if (!$hasBooking) {
            return back()->with('error', 'You must have a confirmed session with this tutor to download materials.');
        }

        $fullPath = public_path($material->file_path);
        if (file_exists($fullPath)) {
            return response()->download($fullPath, $material->file_name);
        }

        return back()->with('error', 'The requested file could not be found.');
    }
}
