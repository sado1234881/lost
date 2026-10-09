<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request and check if user has one of the required roles.
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles Allowed roles (e.g. 'admin', 'staff', 'student')
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();
        $userRole = strtolower($user->role ?? 'user');

        // Map legacy 'user' role to 'student'
        if ($userRole === 'user') {
            $userRole = 'student';
        }

        $allowedRoles = array_map(function ($r) {
            $r = strtolower($r);
            return $r === 'user' ? 'student' : $r;
        }, $roles);

        // Admin override can access staff routes
        if ($userRole === 'admin') {
            return $next($request);
        }

        if (in_array($userRole, $allowedRoles, true)) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['error' => 'Unauthorized action. Required role: ' . implode('/', $roles)], 403);
        }

        abort(403, 'Unauthorized. You do not have permission to access this resource.');
    }
}
