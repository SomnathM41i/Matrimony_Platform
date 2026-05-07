<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\User;
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

        if (!$user->profile || !$user->profile->height_cm) {
            return redirect()->route('user.profile.setup.show', 1)
                             ->with('info', 'Please complete your profile first.');
        }

        $completedStep = $this->setupService->getCompletedStep($user);

        return view('user.profile.my-profile', compact('user', 'completedStep'));
    }

    // =========================================================================
    // PUBLIC PROFILE — View another member's profile by slug
    // GET /user/profile/{slug}  →  user.profile.public
    // =========================================================================

    public function publicProfile(string $slug): View|RedirectResponse
    {
        $user = User::where('profile_slug', $slug)
            ->where('account_status', 'active')
            // ->whereNotNull('email_verified_at')
            ->firstOrFail();

        $user->load([
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
        ]);

        $authUser = Auth::user()->load(['sentInterests', 'shortlists', 'activeSubscription.package']);
        $isSelf = $authUser->id === $user->id;

        if (!$isSelf && $user->profile?->profile_visibility === 'hidden') {
            abort(404);
        }

        // Has auth user sent interest to this profile?
        $interestSent = $authUser->sentInterests
            ->where('receiver_id', $user->id)
            ->first(); // Interest model or null

        // Has auth user shortlisted this profile?
        $isShortlisted = $authUser->shortlists
            ->where('shortlisted_user_id', $user->id)
            ->isNotEmpty();

        // Has the viewed user sent interest to auth user?
        $interestReceived = Interest::where('sender_id', $user->id)
            ->where('receiver_id', $authUser->id)
            ->first(); // Interest model or null

        $hasAcceptedInterest = $interestSent?->status === 'accepted'
            || $interestReceived?->status === 'accepted';
        $viewerHasPremiumPlan = $authUser->activeSubscription?->isValid()
            && (bool) $authUser->activeSubscription->package;
        $viewerCanSeeContactByPlan = $authUser->activeSubscription?->isValid()
            && (bool) $authUser->activeSubscription->package?->can_see_contact;

        $canViewPhotos = $isSelf || $this->privacyAllows(
            $user->profile?->photo_privacy ?? 'all',
            $hasAcceptedInterest,
            $viewerHasPremiumPlan
        );
        $canViewContact = !$isSelf
            && $viewerCanSeeContactByPlan
            && $this->privacyAllows(
                $user->profile?->contact_privacy ?? 'accepted_interest',
                $hasAcceptedInterest,
                $viewerHasPremiumPlan
            );

        $visiblePhotos = $canViewPhotos
            ? ($isSelf ? $user->photos : $user->photos->where('is_approved', true)->values())
            : collect();

        return view('user.profile.public-profile', compact(
            'user',
            'authUser',
            'interestSent',
            'isShortlisted',
            'interestReceived',
            'canViewPhotos',
            'canViewContact',
            'visiblePhotos'
        ));
    }

    private function privacyAllows(string $setting, bool $hasAcceptedInterest, bool $viewerHasPremiumPlan): bool
    {
        return match ($setting) {
            'all' => true,
            'accepted_interest' => $hasAcceptedInterest,
            'premium' => $viewerHasPremiumPlan,
            default => false,
        };
    }

    // =========================================================================
    // EDIT PROFILE — Redirect to the correct incomplete setup step
    // GET /user/profile/me/edit  →  user.profile.edit
    // =========================================================================

    public function editProfile(): RedirectResponse
    {
        $user = Auth::user()->load(['profile', 'photos', 'partnerPreference']);

        if (!$user->profile) {
            return redirect()->route('user.profile.setup.show', 1)
                             ->with('info', 'Start filling your profile.');
        }

        $completedStep = $this->setupService->getCompletedStep($user);

        if ($completedStep >= 7) {
            return redirect()->route('user.profile.setup.show', 1)
                             ->with('info', 'Your profile is complete. You can edit any section below.');
        }

        $nextStep = min($completedStep + 1, 7);

        return redirect()->route('user.profile.setup.show', $nextStep)
                         ->with('info', 'Continue from where you left off.');
    }
}
