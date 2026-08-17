<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact support.');
        }

        if ($user->role !== 'admin') {
            if ($user->role === 'tutor') {
                return redirect()->route('tutor.dashboard')->with('error', 'Unauthorized access to Admin portal.');
            }
            return redirect()->route('student.dashboard')->with('error', 'Unauthorized access to Admin portal.');
        }

        return $next($request);
    }
}
