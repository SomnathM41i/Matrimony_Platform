<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    // ── Forgot password form ──────────────────────────────────────────────────

    public function showForm(): View
    {
        return view('user.auth.forgot-password');
    }

    // ── Send reset link ───────────────────────────────────────────────────────

    public function sendLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Laravel's built-in password broker handles throttling and token generation
        $status = Password::sendResetLink($request->only('email'));

        // Always show a success message to prevent email enumeration
        return back()->with('success', 'If an account exists for that email, a reset link has been sent.');
    }

    // ── Show reset form ───────────────────────────────────────────────────────

    public function showReset(Request $request, string $token): View
    {
        return view('user.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // ── Process reset ─────────────────────────────────────────────────────────

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('user.login')
                             ->with('success', 'Password reset successfully. Please log in.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}