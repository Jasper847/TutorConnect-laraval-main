<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $subjects = Subject::orderBy('name')->get();
        $selectedRole = $request->get('role', 'student');
        return view('auth.register', compact('subjects', 'selectedRole'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,tutor'],
            'city' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            // Conditional validation for Tutor
            'headline' => ['required_if:role,tutor', 'nullable', 'string', 'max:255'],
            'hourly_rate' => ['required_if:role,tutor', 'nullable', 'numeric', 'min:5', 'max:500'],
            'experience_years' => ['required_if:role,tutor', 'nullable', 'integer', 'min:0', 'max:50'],
            'qualification' => ['required_if:role,tutor', 'nullable', 'string', 'max:255'],
            'subjects' => ['nullable', 'array'],
            // Conditional validation for Student
            'grade_level' => ['nullable', 'string', 'max:100'],
            'learning_goals' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'city' => $request->city,
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        if ($user->role === 'tutor') {
            $tutorProfile = TutorProfile::create([
                'user_id' => $user->id,
                'headline' => $request->headline ?: 'Professional Tutor',
                'bio' => $request->bio ?: 'Dedicated and experienced tutor specializing in personalized student learning.',
                'hourly_rate' => $request->hourly_rate ?: 25.00,
                'experience_years' => $request->experience_years ?: 1,
                'qualification' => $request->qualification ?: 'Bachelors Degree',
                'institution' => $request->institution,
                'teaching_mode' => $request->teaching_mode ?: 'both',
                'is_verified' => false,
            ]);

            if ($request->has('subjects') && is_array($request->subjects)) {
                $tutorProfile->subjects()->sync($request->subjects);
            }
        } else {
            StudentProfile::create([
                'user_id' => $user->id,
                'grade_level' => $request->grade_level,
                'learning_goals' => $request->learning_goals,
                'institution' => $request->institution,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        if ($user->isTutor()) {
            return redirect()->route('tutor.dashboard')->with('success', 'Welcome to TutorConnect! Complete your availability and profile to get verified.');
        }

        return redirect()->route('student.dashboard')->with('success', 'Welcome to TutorConnect! Find and book your first tutoring session.');
    }
}
