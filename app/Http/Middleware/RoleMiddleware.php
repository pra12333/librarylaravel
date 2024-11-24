<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        \Log::info('RoleMiddleware: Incoming roles', ['roles' => $roles]);

        // Check if the user is authenticated
        if (!Auth::check()) {
            \Log::info('RoleMiddleware: User not authenticated, redirecting to login');
            return redirect()->route('login')->withErrors('You must be logged in to access this page.');
        }

        $user = Auth::user();
        $userRole = strtolower($user->role);  // Correct attribute access
        \Log::info('RoleMiddleware: User authenticated', ['user_id' => $user->id, 'role' => $userRole]);

        // Convert expected roles to lowercase for case-insensitive comparison
        $roles = array_map('strtolower', $roles);

        // Check if the user's role matches the required roles
        if (!in_array($userRole, $roles)) {
            \Log::info('RoleMiddleware: User role not permitted', ['role' => $userRole, 'expected_roles' => $roles]);
            // Redirect to the homepage with the error message correctly set
            return redirect()->route('User.homepage')->with('error', 'You do not have permission to access this page.');
        }
        

        \Log::info('RoleMiddleware: User role permitted, proceeding to next middleware');
        return $next($request);
    }
}
