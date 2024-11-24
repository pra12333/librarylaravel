<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TestRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        // Log the roles received by the middleware
        Log::info('TestRoleMiddleware: Roles received', ['roles' => $roles]);

        // If no roles are provided, log a message and proceed without role checks
        if (empty($roles)) {
            Log::info('TestRoleMiddleware: No roles provided, skipping role check');
            return $next($request);
        }

        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('You must be logged in to access this page.');
        }

        $user = Auth::user();

        // Check if the user's role matches the required roles
        if (!in_array($user->role, $roles)) {
            Log::info('TestRoleMiddleware: User role not permitted', ['user_role' => $user->role, 'expected_roles' => $roles]);
            return redirect()->route('login')->withErrors('You do not have permission to access this page.');
        }

        return $next($request);
    }
}
