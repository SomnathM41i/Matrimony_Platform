<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\ProfileSetupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(protected ProfileSetupService $setupService)
    {
    }

    // =========================================================================
    // MY PROFILE — Show the authenticated user's full profile
    // GET /user/profile/me  →  user.profile.me
    // =========================================================================

    public function myProfile(): View|RedirectResponse
    {
        $user = Auth::user()->load([
            'profile',
            'profile.religion',
            'profile.caste',
            'profile.subCaste',
            'profile.gotra',
            'profile.community',
            'profile.motherTongue',
            'profile.rashi',
            'profile.nakshatra',
            'profile.educationLevel',
            'profile.profession',
            'profile.annualIncomeRange',
            'profile.country',
            'profile.state',
            'profile.city',
            'profile.area',
            'photos',
            'primaryPhoto',
            'partnerPreference',
            'activeSubscription',
        ]);

        // If profile setup not started, redirect to wizard
        if (!$user->profile || !$user->profile->height_cm) {
            return redirect()->route('user.profile.setup.show', 1)
                             ->with('info', 'Please complete your profile first.');
        }

        $completedStep = $this->setupService->getCompletedStep($user);

        return view('user.profile.my-profile', compact('user', 'completedStep'));
    }

    // =========================================================================
    // EDIT PROFILE — Redirect to the correct incomplete setup step
    // GET /user/profile/me/edit  →  user.profile.edit
    //
    // Logic:
    //   - If profile is fully complete → go to Step 1 to allow editing any section
    //   - If incomplete → go to the first incomplete step
    // =========================================================================

    public function editProfile(): RedirectResponse
    {
        $user = Auth::user()->load(['profile', 'photos', 'partnerPreference']);

        if (!$user->profile) {
            return redirect()->route('user.profile.setup.show', 1)
                             ->with('info', 'Start filling your profile.');
        }

        $completedStep = $this->setupService->getCompletedStep($user);

        // Profile fully complete (all 7 steps done) → let user pick any step from Step 1
        if ($completedStep >= 7) {
            return redirect()->route('user.profile.setup.show', 1)
                             ->with('info', 'Your profile is complete. You can edit any section below.');
        }

        // Go to next incomplete step
        $nextStep = min($completedStep + 1, 7);

        return redirect()->route('user.profile.setup.show', $nextStep)
                         ->with('info', 'Continue from where you left off.');
    }
}