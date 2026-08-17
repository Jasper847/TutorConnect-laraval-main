<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        if (!$request->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact support.');
        }

        if ($request->user()->role !== $role) {
            // Redirect to appropriate dashboard based on actual role
            if ($request->user()->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('error', 'Unauthorized area.');
            } elseif ($request->user()->isTutor()) {
                return redirect()->route('tutor.dashboard')->with('error', 'Unauthorized area.');
            } else {
                return redirect()->route('student.dashboard')->with('error', 'Unauthorized area.');
            }
        }

        return $next($request);
    }
}
