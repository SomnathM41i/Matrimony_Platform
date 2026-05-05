<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    // ── Show registration form ────────────────────────────────────────────────

    public function showRegister(): View
    {
        return view('user.auth.register');
    }

    // ── Process registration ──────────────────────────────────────────────────

    public function store(RegisterRequest $request): RedirectResponse
    {
        $userRole = Role::where('name', 'user')->firstOrFail();

        $user = DB::transaction(function () use ($request, $userRole) {

            // 1. Create the User record
            $user = User::create([
                'role_id'        => $userRole->id,
                'name'           => trim($request->first_name.' '.$request->last_name),
                'email'          => $request->email,
                'phone'          => $request->phone,
                'password'       => Hash::make($request->password),
                'gender'         => $request->gender,
                'date_of_birth'  => $request->date_of_birth,
                'profile_slug'   => $this->generateUniqueSlug($request->first_name, $request->last_name),
                'account_status' => 'active',
                'profile_status' => 'incomplete',
            ]);

            // 2. Create the empty UserProfile row (filled out during wizard)
            UserProfile::create([
                'user_id'               => $user->id,
                'first_name'            => $request->first_name,
                'last_name'             => $request->last_name,
                'completion_percentage' => 0,
            ]);

            return $user;
        });

        // Fire Registered event → triggers SendEmailVerificationNotification
        event(new Registered($user));

        // Log in the user immediately
        Auth::login($user);

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'user_registered',
            'description' => 'New user registered.',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        // return redirect()->route('user.verification.notice')
        //                  ->with('success', 'Account created! Please verify your email to continue.');

        return redirect()->route('user.profile.setup.show', 1)
                ->with('success', 'Account created! Please complete your profile.');
    }

    // ── Email verification ────────────────────────────────────────────────────

    public function verifyEmail(Request $request, int $id, string $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            abort(403, 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard')->with('info', 'Email already verified.');
        }

        $user->markEmailAsVerified();

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'email_verified',
            'description' => 'User verified their email address.',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('user.dashboard')
                         ->with('success', 'Email verified successfully! Complete your profile to get started.');
    }

    // ── Resend verification email ─────────────────────────────────────────────

    public function resendVerification(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'A fresh verification link has been sent to your email.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function generateUniqueSlug(string $firstName, string $lastName): string
    {
        $base = Str::slug($firstName.'-'.$lastName);
        $slug = $base;
        $i    = 1;

        while (User::where('profile_slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}