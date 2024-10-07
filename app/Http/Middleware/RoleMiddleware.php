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
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $role = $user->role;

        // Define the allowed route names for each role
        $allowedRoutes = [
            'Admin' => [
                'admin.dashboard',
                'admin.menu',
            ],

            'Staff' => [
                'staff.patientRecord',
            ],

            'User'  => [
                'user.dashboard',
                'user.menu',
            ],
        ];

        // Check if the current route name is in the allowed routes for the user's role
        if (!in_array($request->route()->getName(), $allowedRoutes[$role] ?? [])) {
            $message = "You can only access {$role}'s files. Please log out first to continue.";
            session()->flash('error', $message);

            // Redirect to the first allowed route (dashboard)
            return back();
        }

        return $next($request);
    }
}
