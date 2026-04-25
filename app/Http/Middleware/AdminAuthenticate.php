<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminAuthenticate
 * Ensures the user is logged in and has an admin-level role.
 * Attach to all protected admin routes.
 */
class AdminAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in at all
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('admin.login')
                             ->with('error', 'Please log in to access the admin panel.');
        }

        $user = Auth::user();

        // Must have a role
        if (!$user->role) {
            Auth::logout();
            return redirect()->route('admin.login')
                             ->with('error', 'Your account has no assigned role. Contact support.');
        }

        // Only allow super_admin, admin, relationship_manager roles into the panel
        $allowedRoles = ['super_admin', 'admin', 'relationship_manager'];
        if (!in_array($user->role->name, $allowedRoles, true)) {
            Auth::logout();
            return redirect()->route('admin.login')
                             ->with('error', 'You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
}
