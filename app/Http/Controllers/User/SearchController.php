<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AnnualIncomeRange;
use App\Models\Caste;
use App\Models\City;
use App\Models\Country;
use App\Models\EducationLevel;
use App\Models\Interest;
use App\Models\Profession;
use App\Models\Religion;
use App\Models\SavedSearch;
use App\Models\Shortlist;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SearchController extends Controller
{
    // =========================================================================
    // SEARCH INDEX
    // GET /user/search  →  user.search.index
    // =========================================================================

    public function index(Request $request): View
    {
        $authUser = Auth::user()->load([
            'sentInterests',
            'shortlists',
            'partnerPreference',
        ]);

        // ── Filter data for dropdowns ──────────────────────────────────────
        $religions      = Religion::orderBy('name')->get();
        $educationLevels= EducationLevel::orderBy('name')->get();
        $professions    = Profession::orderBy('name')->get();
        $countries      = Country::orderBy('name')->get();
        $incomeRanges   = AnnualIncomeRange::orderBy('id')->get();

        // Pre-fetch states/cities only if a country/state is already selected
        $states = $request->filled('country_id')
            ? State::where('country_id', $request->country_id)->orderBy('name')->get()
            : collect();
        $cities = $request->filled('state_id')
            ? City::where('state_id', $request->state_id)->orderBy('name')->get()
            : collect();
        $castes = $request->filled('religion_id')
            ? Caste::where('religion_id', $request->religion_id)->orderBy('name')->get()
            : collect();

        // Saved searches for this user
        $savedSearches = SavedSearch::where('user_id', $authUser->id)
            ->latest()
            ->take(5)
            ->get();

        // ── Run search only if any filter is present ───────────────────────
        $results    = null;
        $totalCount = 0;
        $filters    = [];
//dd($results);
        if ($request->hasAny([
            'keyword','age_min','age_max','religion_id','caste_id',
            'marital_status','height_min','height_max','country_id',
            'state_id','city_id','education_level_id','profession_id',
            'annual_income_range_id','diet','body_type','complexion',
            'manglik_status','mother_tongue_id','with_photo',
        ])) {
            [$results, $totalCount, $filters] = $this->runSearch($request, $authUser);
        }

        return view('user.search.index', compact(
            'authUser', 'results', 'totalCount', 'filters',
            'religions', 'educationLevels', 'professions',
            'countries', 'states', 'cities', 'castes', 'incomeRanges',
            'savedSearches'
        ));
    }

    // =========================================================================
    // SAVE SEARCH
    // POST /user/search/save  →  user.search.save
    // =========================================================================

    public function saveSearch(Request $request): RedirectResponse
    {
        $request->validate([
            'search_name' => 'required|string|max:80',
        ]);

        $filters = $request->except(['_token', 'search_name', 'page']);

        SavedSearch::create([
            'user_id'     => Auth::id(),
            'name'        => $request->search_name,
            'search_type' => 'advanced',
            'filters'     => $filters,
            'keyword'     => $request->keyword,
            'last_run_at' => now(),
        ]);

        return back()->with('success', 'Search saved successfully.');
    }

    // =========================================================================
    // DELETE SAVED SEARCH
    // DELETE /user/search/saved/{id}  →  user.search.saved.delete
    // =========================================================================

    public function deleteSavedSearch(SavedSearch $savedSearch): RedirectResponse
    {
        abort_if($savedSearch->user_id !== Auth::id(), 403);
        $savedSearch->delete();
        return back()->with('success', 'Saved search deleted.');
    }

    // =========================================================================
    // PRIVATE — QUERY BUILDER
    // =========================================================================

    private function runSearch(Request $request, User $authUser): array
    {
        $oppositeGender = $authUser->gender === 'male' ? 'female' : 'male';

        $query = User::query()
            ->where('id', '!=', $authUser->id)
            ->where('gender', $oppositeGender)
            ->where('account_status', 'active')
            ->whereNotNull('email_verified_at')
            ->with([
                'profile',
                'profile.religion',
                'profile.caste',
                'profile.educationLevel',
                'profile.profession',
                'profile.city',
                'profile.state',
                'primaryPhoto',
            ]);

        $filters = [];

        // ── Keyword (name / profile slug) ─────────────────────────────────
        if ($request->filled('keyword')) {
            $kw = '%' . $request->keyword . '%';
            $filters['keyword'] = $request->keyword;
            $query->where(function ($q) use ($kw) {
                $q->where('name', 'like', $kw)
                  ->orWhere('profile_slug', 'like', $kw)
                  ->orWhereHas('profile', fn($pq) =>
                        $pq->where('first_name', 'like', $kw)
                           ->orWhere('last_name',  'like', $kw)
                  );
            });
        }

        // ── Age ───────────────────────────────────────────────────────────
        $ageMin = (int) $request->input('age_min', 18);
        $ageMax = (int) $request->input('age_max', 70);
        $filters['age_min'] = $ageMin;
        $filters['age_max'] = $ageMax;
        $query->whereRaw(
            'TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN ? AND ?',
            [$ageMin, $ageMax]
        );

        // ── Height ────────────────────────────────────────────────────────
        if ($request->filled('height_min') || $request->filled('height_max')) {
            $hMin = (int) $request->input('height_min', 120);
            $hMax = (int) $request->input('height_max', 220);
            $filters['height_min'] = $hMin;
            $filters['height_max'] = $hMax;
            $query->whereHas('profile', fn($q) => $q->whereBetween('height_cm', [$hMin, $hMax]));
        }

        // ── Religion ──────────────────────────────────────────────────────
        if ($request->filled('religion_id')) {
            $filters['religion_id'] = $request->religion_id;
            $query->whereHas('profile', fn($q) => $q->where('religion_id', $request->religion_id));
        }

        // ── Caste ─────────────────────────────────────────────────────────
        if ($request->filled('caste_id')) {
            $filters['caste_id'] = $request->caste_id;
            $query->whereHas('profile', fn($q) => $q->where('caste_id', $request->caste_id));
        }

        // ── Marital Status ────────────────────────────────────────────────
        if ($request->filled('marital_status')) {
            $ms = (array) $request->marital_status;
            $filters['marital_status'] = $ms;
            $query->whereHas('profile', fn($q) => $q->whereIn('marital_status', $ms));
        }

        // ── Education ─────────────────────────────────────────────────────
        if ($request->filled('education_level_id')) {
            $filters['education_level_id'] = $request->education_level_id;
            $query->whereHas('profile', fn($q) => $q->where('education_level_id', $request->education_level_id));
        }

        // ── Profession ────────────────────────────────────────────────────
        if ($request->filled('profession_id')) {
            $filters['profession_id'] = $request->profession_id;
            $query->whereHas('profile', fn($q) => $q->where('profession_id', $request->profession_id));
        }

        // ── Income ────────────────────────────────────────────────────────
        if ($request->filled('annual_income_range_id')) {
            $filters['annual_income_range_id'] = $request->annual_income_range_id;
            $query->whereHas('profile', fn($q) => $q->where('annual_income_range_id', $request->annual_income_range_id));
        }

        // ── Location ──────────────────────────────────────────────────────
        if ($request->filled('city_id')) {
            $filters['city_id'] = $request->city_id;
            $query->whereHas('profile', fn($q) => $q->where('city_id', $request->city_id));
        } elseif ($request->filled('state_id')) {
            $filters['state_id'] = $request->state_id;
            $query->whereHas('profile', fn($q) => $q->where('state_id', $request->state_id));
        } elseif ($request->filled('country_id')) {
            $filters['country_id'] = $request->country_id;
            $query->whereHas('profile', fn($q) => $q->where('country_id', $request->country_id));
        }

        // ── Lifestyle ─────────────────────────────────────────────────────
        foreach (['diet', 'body_type', 'complexion', 'manglik_status'] as $field) {
            if ($request->filled($field)) {
                $filters[$field] = $request->$field;
                $query->whereHas('profile', fn($q) => $q->where($field, $request->$field));
            }
        }

        // ── Mother tongue ─────────────────────────────────────────────────
        if ($request->filled('mother_tongue_id')) {
            $filters['mother_tongue_id'] = $request->mother_tongue_id;
            $query->whereHas('profile', fn($q) => $q->where('mother_tongue_id', $request->mother_tongue_id));
        }

        // ── With photo ────────────────────────────────────────────────────
        if ($request->input('with_photo') === '1') {
            $filters['with_photo'] = true;
            $query->whereHas('photos');
        }

        // ── Sort ──────────────────────────────────────────────────────────
        $sort = $request->input('sort', 'last_active');
        $filters['sort'] = $sort;
        match ($sort) {
            'newest'      => $query->orderByDesc('created_at'),
            'age_asc'     => $query->orderBy('date_of_birth', 'desc'),
            'age_desc'    => $query->orderBy('date_of_birth', 'asc'),
            'completion'  => $query->join('user_profiles as up_s', 'users.id', '=', 'up_s.user_id')
                                   ->orderByDesc('up_s.completion_percentage')
                                   ->select('users.*'),
            default       => $query->orderByDesc('last_login_at'),
        };

        $total   = $query->count();
        $results = $query->paginate(12)->withQueryString();

        // Enrich
        $sentIds      = $authUser->sentInterests->pluck('receiver_id')->flip();
        $shortlistIds = $authUser->shortlists->pluck('shortlisted_user_id')->flip();

        $results->getCollection()->transform(function (User $u) use ($sentIds, $shortlistIds) {
            $u->interest_sent  = $sentIds->has($u->id);
            $u->is_shortlisted = $shortlistIds->has($u->id);
            return $u;
        });

        return [$results, $total, $filters];
    }
}