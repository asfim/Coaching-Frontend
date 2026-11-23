<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'))->with('error', 'Please log in to continue.');
        }

        $user = Auth::user();

        // If roles specified, verify user has one of the allowed roles
        if (!empty($roles)) {
            // Admin has universal superuser access
            if ($user->role === 'admin') {
                return $next($request);
            }

            if (!in_array($user->role, $roles, true)) {
                return match ($user->role) {
                    'teacher' => redirect()->route('teacher.dashboard')->with('error', 'Access restricted to authorized personnel.'),
                    'student' => redirect()->route('student.dashboard')->with('error', 'Access restricted to authorized personnel.'),
                    'parent' => redirect()->route('parent.dashboard')->with('error', 'Access restricted to authorized personnel.'),
                    default => redirect()->route('home')->with('error', 'Unauthorized access.'),
                };
            }
        }

        return $next($request);
    }
}
