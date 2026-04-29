<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\Setting;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user()->load([
            'profile',
            'profile.religion',
            'profile.city',
            'profile.state',
            'profile.educationLevel',
            'activeSubscription',
            'primaryPhoto',
            'photos',
            'partnerPreference',
        ]);

        // ── Dashboard statistics ───────────────────────────────────────────
        $stats = [
            // Profile completion percentage from UserProfile
            'profile_completion' => $user->profile?->completion_percentage ?? 0,

            // Unread interests received (pending)
            'unread_interests' => Interest::where('receiver_id', $user->id)
                                          ->where('status', 'pending')
                                          ->count(),

            // Accepted interests (mutual connections)
            'accepted_interests' => Interest::where(function ($q) use ($user) {
                    $q->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
                })
                ->where('status', 'accepted')
                ->count(),

            // Profile views in last 30 days
            'profile_views_month' => $user->profileViews()
                                          ->where('viewed_at', '>=', now()->subDays(30))
                                          ->count(),

            // Shortlists received
            'shortlisted_by_count' => $user->shortlistedBy()->count(),

            // Active subscription details
            'subscription' => $user->activeSubscription,
        ];

        // ── Completion steps for the banner prompt ─────────────────────────
        $completionSteps = $this->getMissingProfileSteps($user);

        // ── Quick notices pulled from Settings ─────────────────────────────
        $siteNotice = Setting::get('site_notice');

        return view('user.dashboard', compact('user', 'stats', 'completionSteps', 'siteNotice'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Returns an array of incomplete profile sections with label + route.
     * Used to render the stepped completion banner on the dashboard.
     *
     * Route name: user.profile.setup.show  (was: user.profile.setup)
     * Parameter : step number (int)
     */
    private function getMissingProfileSteps($user): array
    {
        $profile = $user->profile;
        $steps   = [];

        // No profile row at all — send to Step 1
        if (!$profile) {
            return [[
                'label' => 'Start your profile',
                'route' => route('user.profile.setup.show', ['step' => 1]),
                'step'  => 1,
            ]];
        }

        // Step 1 — Basic Info (height is a reliable indicator)
        if (!$profile->height_cm) {
            $steps[] = [
                'label' => 'Add basic information',
                'route' => route('user.profile.setup.show', ['step' => 1]),
                'step'  => 1,
            ];
        }

        // Step 2 — Religion & Community
        if (!$profile->religion_id) {
            $steps[] = [
                'label' => 'Add religious information',
                'route' => route('user.profile.setup.show', ['step' => 2]),
                'step'  => 2,
            ];
        }

        // Step 4 — Education & Career
        if (!$profile->education_level_id) {
            $steps[] = [
                'label' => 'Add education & career',
                'route' => route('user.profile.setup.show', ['step' => 4]),
                'step'  => 4,
            ];
        }

        // Step 5 — Location & Family
        if (!$profile->country_id) {
            $steps[] = [
                'label' => 'Add location & family',
                'route' => route('user.profile.setup.show', ['step' => 5]),
                'step'  => 5,
            ];
        }

        // Step 6 — Partner Preferences
        // Using relationship loaded via ->load() — no extra query
        if (!$user->partnerPreference) {
            $steps[] = [
                'label' => 'Set partner preferences',
                'route' => route('user.profile.setup.show', ['step' => 6]),
                'step'  => 6,
            ];
        }

        // Step 7 — Photos
        // Using photos collection loaded via ->load() — no extra query
        if ($user->photos->isEmpty()) {
            $steps[] = [
                'label' => 'Upload a photo',
                'route' => route('user.profile.setup.show', ['step' => 7]),
                'step'  => 7,
            ];
        }

        return $steps;
    }
}