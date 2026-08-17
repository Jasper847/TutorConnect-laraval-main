<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $user->load('studentProfile');
        return view('student.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'grade_level' => ['nullable', 'string', 'max:100'],
            'learning_goals' => ['nullable', 'string', 'max:1000'],
            'institution' => ['nullable', 'string', 'max:255'],
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

        $user->studentProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'grade_level' => $request->grade_level,
                'learning_goals' => $request->learning_goals,
                'institution' => $request->institution,
            ]
        );

        return back()->with('success', 'Profile updated successfully!');
    }
}
