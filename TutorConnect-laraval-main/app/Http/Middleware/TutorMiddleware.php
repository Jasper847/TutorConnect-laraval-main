<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TutorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated.');
        }

        if ($user->role !== 'tutor') {
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('student.dashboard')->with('error', 'Unauthorized access to Tutor portal.');
        }

        // Profile existence check
        if (!$user->tutorProfile && !$request->routeIs('tutor.profile.*')) {
            $user->tutorProfile()->create([
                'hourly_rate' => 25.00,
                'experience_years' => 1,
                'is_available' => true,
            ]);
        }

        return $next($request);
    }
}
