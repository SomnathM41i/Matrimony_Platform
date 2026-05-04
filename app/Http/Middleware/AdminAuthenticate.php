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
// Http/Middleware/AdminAuthenticate.php

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please log in.');
        }

        $user = Auth::user();

        // ✅ Allow impersonating sessions through — stopImpersonation handles re-auth
        if (session('impersonating') && session('impersonator_id')) {
            return $next($request);
        }

        if (!$user->role) { /* ... */ }

        $allowedRoles = ['super_admin', 'admin', 'relationship_manager'];
        if (!in_array($user->role->name, $allowedRoles, true)) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}
