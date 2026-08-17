<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $user->load('tutorProfile.subjects');
        $subjects = Subject::orderBy('name')->get();
        return view('tutor.profile.edit', compact('user', 'subjects'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'headline' => ['required', 'string', 'max:255'],
            'bio' => ['required', 'string', 'min:20', 'max:3000'],
            'hourly_rate' => ['required', 'numeric', 'min:5', 'max:500'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'qualification' => ['required', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'teaching_mode' => ['required', 'in:online,in_person,both'],
            'subjects' => ['nullable', 'array'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->city = $request->city;

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename);
            $user->avatar = 'uploads/avatars/' . $filename;
        }

        $user->save();

        $tutorProfile = $user->tutorProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'headline' => $request->headline,
                'bio' => $request->bio,
                'hourly_rate' => $request->hourly_rate,
                'experience_years' => $request->experience_years,
                'qualification' => $request->qualification,
                'institution' => $request->institution,
                'teaching_mode' => $request->teaching_mode,
            ]
        );

        if ($request->has('subjects')) {
            $tutorProfile->subjects()->sync($request->subjects);
        } else {
            $tutorProfile->subjects()->detach();
        }

        return back()->with('success', 'Tutor profile updated successfully!');
    }
}
