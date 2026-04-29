<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureUserRole
 *
 * Ensures the authenticated user has the 'user' role.
 * Prevents Super Admins and Relationship Managers from accidentally
 * accessing the end-user panel with their admin credentials.
 *
 * Admin-role users are redirected to the admin dashboard.
 * Unauthenticated requests are redirected to the user login page.
 */
class EnsureUserRole
{
    private const USER_ROLE = 'user';

    private const ADMIN_ROLES = ['super_admin', 'admin', 'relationship_manager'];

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        $role = Auth::user()->role?->name;

        // Block admin/RM accounts from the user panel
        if (in_array($role, self::ADMIN_ROLES, true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Admin accounts must use the admin panel.',
                ], Response::HTTP_FORBIDDEN);
            }
            return redirect()->route('admin.dashboard')
                             ->with('info', 'Please use the admin panel.');
        }

        // Accounts with no role or unknown role are rejected
        if ($role !== self::USER_ROLE) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('user.login')
                             ->with('error', 'Your account role is not configured. Please contact support.');
        }

        return $next($request);
    }
}