<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    // ── Show login form ───────────────────────────────────────────────────────

    public function showLogin(): View
    {
        return view('user.auth.login');
    }

    // ── Process login ─────────────────────────────────────────────────────────

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Rate-limit: 5 attempts per minute per email+IP combo
        $throttleKey = Str::lower($request->email).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $user = Auth::user();

        // Reject admins / RMs trying to log in through user panel
        if (in_array($user->role?->name, ['super_admin', 'admin', 'relationship_manager'], true)) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Admin accounts must log in through the admin panel.',
            ]);
        }

        // Persist last login metadata
        $user->update([
            'last_login_ip' => $request->ip(),
            'last_login_at' => now(),
        ]);

        // Log the activity
        ActivityLog::create([
            'user_id'    => $user->id,
            'action'     => 'user_login',
            'description'=> 'User logged in from IP: '.$request->ip(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $request->session()->regenerate();

        return redirect()->intended(route('user.dashboard'));
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($userId) {
            ActivityLog::create([
                'user_id'     => $userId,
                'action'      => 'user_logout',
                'description' => 'User logged out.',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        }

        return redirect()->route('user.login')
                         ->with('success', 'You have been logged out successfully.');
    }
}