<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TutorAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function index()
    {
        $tutorProfile = Auth::user()->tutorProfile;
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $availabilities = $tutorProfile->availabilities->keyBy('day_of_week');

        return view('tutor.availability.index', compact('days', 'availabilities'));
    }

    public function update(Request $request)
    {
        $tutorProfile = Auth::user()->tutorProfile;
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($days as $day) {
            $isAvailable = $request->has("days.{$day}.enabled");
            $startTime = $request->input("days.{$day}.start_time", '09:00');
            $endTime = $request->input("days.{$day}.end_time", '17:00');

            TutorAvailability::updateOrCreate(
                [
                    'tutor_profile_id' => $tutorProfile->id,
                    'day_of_week' => $day,
                ],
                [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_available' => $isAvailable,
                ]
            );
        }

        return back()->with('success', 'Weekly availability schedule saved successfully!');
    }
}
