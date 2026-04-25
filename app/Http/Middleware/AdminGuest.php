<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminGuest
 * Redirects already-authenticated admin users away from login/register pages.
 */
class AdminGuest
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $role = Auth::user()->role?->name;
            $adminRoles = ['super_admin', 'admin', 'relationship_manager'];

            if (in_array($role, $adminRoles, true)) {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
