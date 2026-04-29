<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * UserGuest
 *
 * Redirects already-authenticated end-users away from guest pages
 * (login, register, forgot-password).
 *
 * Admins and Relationship Managers are NOT redirected here — they
 * have their own separate panel and should not land on the user dashboard.
 */
class UserGuest
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $role = Auth::user()->role?->name;

            // Only end-users get redirected to the user dashboard
            if ($role === 'user') {
                return redirect()->route('user.dashboard');
            }

            // Admins/RMs trying to hit user guest pages → send to admin panel
            if (in_array($role, ['super_admin', 'admin', 'relationship_manager'], true)) {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}