<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CompatibilityScore;
use App\Models\Interest;
use App\Models\Shortlist;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MatchesController extends Controller
{
    // =========================================================================
    // MATCHES INDEX
    // GET /user/matches  →  user.matches.index
    // =========================================================================

    public function index(Request $request): View
    {
        $authUser = Auth::user()->load([
            'profile',
            'profile.religion',
            'profile.caste',
            'profile.city',
            'profile.state',
            'partnerPreference',
            'primaryPhoto',
            'sentInterests',
            'shortlists',
        ]);

        $pref = $authUser->partnerPreference;

        // ── Base query: opposite gender, active, verified ──────────────────
        $oppositeGender = $authUser->gender === 'male' ? 'female' : 'male';
// dd($oppositeGender);
        //  $query = User::query()
        //     ->where('id', '!=', $authUser->id)
        //     ->where('gender', $oppositeGender)
        //     ->where('account_status', 'active')
        //     ->whereNotNull('email_verified_at')
        //     ->whereHas('profile', fn($q) => $q->whereNotNull('height_cm'))
        //     ->with([
        //         'profile',
        //         'profile.religion',
        //         'profile.caste',
        //         'profile.city',
        //         'profile.state',
        //         'profile.educationLevel',
        //         'profile.profession',
        //         'primaryPhoto',
        //     ]);

        $query = User::where('gender', $oppositeGender)
            ->where('id', '!=', $authUser->id)
            ->select('id', 'profile_slug', 'name', 'gender');

        // ── Filters from request ───────────────────────────────────────────
        $filters = $this->applyFilters($query, $request, $authUser, $pref);

        // ── Sort ───────────────────────────────────────────────────────────
        $sort = $request->input('sort', 'relevance');
        $this->applySort($query, $sort, $authUser->id);

        $matches = $query->paginate(12)->withQueryString();

        // foreach ($matches as $match) {
            
        //    // dd($match->profile_slug);
        // }
// dd($matches->toArray());
        // ── Enrich: add interest/shortlist status per match ────────────────
        $sentInterestUserIds = $authUser->sentInterests->pluck('receiver_id')->flip();
        $shortlistedUserIds  = $authUser->shortlists->pluck('shortlisted_user_id')->flip();

        $matches->getCollection()->transform(function (User $match) use ($sentInterestUserIds, $shortlistedUserIds, $authUser) {
            $match->interest_sent  = $sentInterestUserIds->has($match->id);
            $match->is_shortlisted = $shortlistedUserIds->has($match->id);
            $match->compat_score    = $this->quickCompatScore($authUser, $match);
            return $match;
        });

        // ── Stats for sidebar ──────────────────────────────────────────────
        $stats = [
            'total_matches'    => $matches->total(),
            'interests_sent'   => $authUser->sentInterests->count(),
            'interests_pending'=> Interest::where('receiver_id', $authUser->id)->where('status','pending')->count(),
        ];

        return view('user.matches.index', compact(
            'authUser', 'matches', 'pref', 'filters', 'sort', 'stats'
        ));
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Apply request filters AND partner-preference-based defaults.
     * Returns the active filter values for the view.
     */
    private function applyFilters($query, Request $request, User $authUser, $pref): array
    {
        $filters = [];

        // Age range
        $ageMin = (int) $request->input('age_min', $pref?->age_min ?? 18);
        $ageMax = (int) $request->input('age_max', $pref?->age_max ?? 60);
        $filters['age_min'] = $ageMin;
        $filters['age_max'] = $ageMax;

        $query->whereHas('profile', function ($q) use ($ageMin, $ageMax) {
            // date_of_birth stored on users table
        })->whereRaw(
            'TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN ? AND ?',
            [$ageMin, $ageMax]
        );

        // Height range
        if ($request->filled('height_min') || $pref?->height_min_cm) {
            $hMin = (int) $request->input('height_min', $pref?->height_min_cm);
            $hMax = (int) $request->input('height_max', $pref?->height_max_cm ?? 220);
            $filters['height_min'] = $hMin;
            $filters['height_max'] = $hMax;
            $query->whereHas('profile', fn($q) => $q->whereBetween('height_cm', [$hMin, $hMax]));
        }

        // Religion
        $religionIds = $request->input('religion_ids', $pref?->religion_ids ?? []);
        if (!empty($religionIds)) {
            $filters['religion_ids'] = $religionIds;
            $query->whereHas('profile', fn($q) => $q->whereIn('religion_id', $religionIds));
        }

        // Marital status
        $maritalStatus = $request->input('marital_status', $pref?->marital_status ?? []);
        if (!empty($maritalStatus)) {
            $filters['marital_status'] = $maritalStatus;
            $query->whereHas('profile', fn($q) => $q->whereIn('marital_status', $maritalStatus));
        }

        // City / State
        if ($request->filled('city_id')) {
            $filters['city_id'] = $request->input('city_id');
            $query->whereHas('profile', fn($q) => $q->where('city_id', $filters['city_id']));
        } elseif ($request->filled('state_id')) {
            $filters['state_id'] = $request->input('state_id');
            $query->whereHas('profile', fn($q) => $q->where('state_id', $filters['state_id']));
        }

        // Education
        $educationIds = $request->input('education_level_ids', $pref?->education_level_ids ?? []);
        if (!empty($educationIds)) {
            $filters['education_level_ids'] = $educationIds;
            $query->whereHas('profile', fn($q) => $q->whereIn('education_level_id', $educationIds));
        }

        // Premium only toggle
        if ($request->boolean('premium_only')) {
            $filters['premium_only'] = true;
            $query->where('is_premium', true);
        }

        // With photo only (default on)
        if ($request->input('with_photo', '1') === '1') {
            $filters['with_photo'] = true;
            $query->whereHas('photos');
        }

        return $filters;
    }

    /**
     * Apply sort to query.
     */
    private function applySort($query, string $sort, int $authUserId): void
    {
        match ($sort) {
            'newest'       => $query->orderByDesc('created_at'),
            'last_active'  => $query->orderByDesc('last_login_at'),
            'age_asc'      => $query->orderBy('date_of_birth', 'desc'),   // younger last
            'age_desc'     => $query->orderBy('date_of_birth', 'asc'),    // older first
            'completion'   => $query->join('user_profiles as up_sort', 'users.id', '=', 'up_sort.user_id')
                                    ->orderByDesc('up_sort.completion_percentage')
                                    ->select('users.*'),
            default        => $query->orderByDesc('is_premium')->orderByDesc('last_login_at'),
        };
    }

    /**
     * Quick in-PHP compatibility score (0–100) for display.
     * A heavy version would use the CompatibilityScore table populated by a job.
     */
    private function quickCompatScore(User $authUser, User $match): int
    {
        // Check pre-computed score first
        $precomputed = CompatibilityScore::where('user_id', $authUser->id)
            ->where('match_user_id', $match->id)
            ->first();

        if ($precomputed) {
            return (int) $precomputed->overall_score;
        }

        $score = 0;
        $pref  = $authUser->partnerPreference;
        $mp    = $match->profile;

        if (!$pref || !$mp) return 50; // neutral default

        // Religion match (25 pts)
        if (!empty($pref->religion_ids) && $mp->religion_id) {
            if (in_array($mp->religion_id, $pref->religion_ids)) $score += 25;
        } else {
            $score += 15; // no preference = partial credit
        }

        // Age range (20 pts)
        $matchAge = $match->date_of_birth?->age;
        if ($matchAge && $pref->age_min && $pref->age_max) {
            if ($matchAge >= $pref->age_min && $matchAge <= $pref->age_max) $score += 20;
        } else {
            $score += 10;
        }

        // Location (20 pts)
        $authProfile = $authUser->profile;
        if ($authProfile && $mp->city_id && $authProfile->city_id === $mp->city_id) {
            $score += 20;
        } elseif ($authProfile && $mp->state_id && $authProfile->state_id === $mp->state_id) {
            $score += 12;
        } elseif ($authProfile && $mp->country_id && $authProfile->country_id === $mp->country_id) {
            $score += 6;
        }

        // Education (15 pts)
        if (!empty($pref->education_level_ids) && $mp->education_level_id) {
            if (in_array($mp->education_level_id, $pref->education_level_ids)) $score += 15;
        } else {
            $score += 8;
        }

        // Marital status (10 pts)
        if (!empty($pref->marital_status) && $mp->marital_status) {
            if (in_array($mp->marital_status, $pref->marital_status)) $score += 10;
        } else {
            $score += 5;
        }

        // Profile completeness bonus (10 pts)
        $score += (int) round(($mp->completion_percentage ?? 0) / 10);

        return min(100, $score);
    }
}