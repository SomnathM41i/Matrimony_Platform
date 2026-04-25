<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminPermission
 * Gate-checks a named permission against the user's role.
 * Super admin always passes.
 *
 * Usage in routes:
 *   ->middleware('admin.permission:manage_users')
 *   ->middleware('admin.permission:manage_cms,manage_seo')  // any of these
 */
class AdminPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = Auth::user();

        // Super admin bypasses all permission checks
        if ($user?->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has at least one of the required permissions
        foreach ($permissions as $permission) {
            if ($user?->hasPermission($permission)) {
                return $next($request);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Forbidden. Insufficient permissions.'], 403);
        }

        abort(403, 'You do not have permission to perform this action.');
    }
}
