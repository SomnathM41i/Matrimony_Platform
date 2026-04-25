<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminActive
 * Ensures the authenticated admin's account_status is 'active'.
 * Soft-blocks suspended/banned admins without deleting their session.
 */
class AdminActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->account_status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                             ->with('error', 'Your admin account has been suspended. Please contact the super administrator.');
        }

        return $next($request);
    }
}
