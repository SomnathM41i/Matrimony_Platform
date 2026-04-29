<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * UserActive
 *
 * Ensures the authenticated user's account_status is 'active'.
 * - 'inactive'  → account deactivated by user themselves
 * - 'suspended' → suspended by admin
 * - 'banned'    → permanently banned
 *
 * On failure: logs out, destroys session, redirects to login with reason.
 * JSON requests get a 403 response.
 */
class UserActive
{
    private const ACTIVE_STATUS = 'active';

    private const STATUS_MESSAGES = [
        'inactive'  => 'Your account is deactivated. Please contact support to reactivate.',
        'suspended' => 'Your account has been suspended. Please contact support for assistance.',
        'banned'    => 'Your account has been permanently banned. Please contact support if you believe this is an error.',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->account_status !== self::ACTIVE_STATUS) {

            $message = self::STATUS_MESSAGES[$user->account_status]
                       ?? 'Your account is not active. Please contact support.';

            // Terminate the session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'status'  => $user->account_status,
                ], Response::HTTP_FORBIDDEN);
            }

            return redirect()->route('user.login')->with('error', $message);
        }

        return $next($request);
    }
}