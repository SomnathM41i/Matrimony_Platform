<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Setup\Step1Request;
use App\Http\Requests\User\Setup\Step2Request;
use App\Http\Requests\User\Setup\Step3Request;
use App\Http\Requests\User\Setup\Step4Request;
use App\Http\Requests\User\Setup\Step5Request;
use App\Http\Requests\User\Setup\Step6Request;
use App\Http\Requests\User\Setup\Step7Request;
use App\Models\AnnualIncomeRange;
use App\Models\Caste;
use App\Models\City;
use App\Models\Community;
use App\Models\Country;
use App\Models\EducationLevel;
use App\Models\Gotra;
use App\Models\Language;
use App\Models\MotherTongue;
use App\Models\Nakshatra;
use App\Models\PartnerPreference;
use App\Models\Profession;
use App\Models\Rashi;
use App\Models\Religion;
use App\Models\Area;
use App\Models\State;
use App\Models\SubCaste;
use App\Services\User\ProfileSetupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileSetupController extends Controller
{
    public function __construct(protected ProfileSetupService $setupService)
    {
    }

    // =========================================================================
    // SHOW — Render the step view
    // GET /user/profile/setup/{step}  →  user.profile.setup.show
    // =========================================================================

    public function show(int $step): View|RedirectResponse
    {
        $user    = Auth::user()->load(['profile', 'partnerPreference', 'photos', 'activeSubscription.package']);
        $profile = $user->profile;

        // Guard: if no profile row exists yet, force Step 1
        if (!$profile && $step > 1) {
            return redirect()->route('user.profile.setup.show', 1)
                             ->with('info', 'Please complete Step 1 first.');
        }

        // Guard: prevent skipping ahead more than 1 step
        $completed = $this->setupService->getCompletedStep($user);
        if ($step > $completed + 1) {
            return redirect()->route('user.profile.setup.show', $completed + 1)
                             ->with('info', 'Please complete the steps in order.');
        }

        $data = $this->getStepViewData($step, $user);

        return view("user.profile.setup.step-{$step}", array_merge($data, [
            'user'    => $user,
            'profile' => $profile,
            'step'    => $step,
        ]));
    }

    // =========================================================================
    // SAVE — Validate + persist the step data
    // POST /user/profile/setup/{step}  →  user.profile.setup.save
    // =========================================================================

    public function save(int $step, \Illuminate\Http\Request $request): RedirectResponse
    {
        $user = Auth::user()->load(['profile', 'photos', 'activeSubscription.package']);

        // Resolve the correct FormRequest per step and validate
        $validated = $this->resolveAndValidate($step, $request);

        // Delegate saving to the service layer
        $this->setupService->saveStep($step, $user, $validated);

        // Recalculate completion after every save
        $user->profile->fresh()->recalculateCompletion();

        // If final step, mark profile as complete and go to dashboard
        if ($step === 7) {
            $user->update(['profile_status' => 'complete']);

            return redirect()->route('user.dashboard')
                             ->with('success', 'Profile setup complete! Welcome to ' . config('app.name') . '.');
        }

        return redirect()->route('user.profile.setup.show', $step + 1)
                         ->with('success', 'Step ' . $step . ' saved successfully.');
    }

    // =========================================================================
    // SKIP — Only step 3 (Horoscope) is skippable
    // POST /user/profile/setup/skip/{step}  →  user.profile.setup.skip
    // =========================================================================

    public function skip(int $step): RedirectResponse
    {
        // Route constraint already ensures only step=3 reaches here
        return redirect()->route('user.profile.setup.show', $step + 1)
                         ->with('info', 'Horoscope step skipped. You can fill it later from your profile.');
    }

    // =========================================================================
    // AJAX — Cascading Dropdowns
    // All return JSON: [{ id: 1, name: 'Hindu' }, ...]
    // =========================================================================

    // Step 2: Religion → Castes
    public function castesByReligion(int $religion): JsonResponse
    {
        $castes = Caste::where('religion_id', $religion)
                       ->where('is_active', true)
                       ->orderBy('sort_order')
                       ->orderBy('name')
                       ->get(['id', 'name']);

        return response()->json($castes);
    }

    // Step 2: Caste → Sub-Castes
    public function subCastesByCaste(int $caste): JsonResponse
    {
        $subCastes = SubCaste::where('caste_id', $caste)
                             ->where('is_active', true)
                             ->orderBy('name')
                             ->get(['id', 'name']);

        return response()->json($subCastes);
    }

    // Step 2: Religion → Gotras
    public function gotrasByReligion(int $religion): JsonResponse
    {
        $gotras = Gotra::where('religion_id', $religion)
                       ->where('is_active', true)
                       ->orderBy('name')
                       ->get(['id', 'name']);

        return response()->json($gotras);
    }

    // Step 2: Religion → Communities
    public function communitiesByReligion(int $religion): JsonResponse
    {
        $communities = Community::where('religion_id', $religion)
                                ->where('is_active', true)
                                ->orderBy('sort_order')
                                ->orderBy('name')
                                ->get(['id', 'name']);

        return response()->json($communities);
    }

    // Step 5: Country → States
    public function statesByCountry(int $country): JsonResponse
    {
        $states = State::where('country_id', $country)
                       ->where('is_active', true)
                       ->orderBy('name')
                       ->get(['id', 'name']);

        return response()->json($states);
    }

    // Step 5: State → Cities
    public function citiesByState(int $state): JsonResponse
    {
        $cities = City::where('state_id', $state)
                      ->where('is_active', true)
                      ->orderBy('sort_order')
                      ->orderBy('name')
                      ->get(['id', 'name']);

        return response()->json($cities);
    }

    // Step 5: City → Areas
    public function areasByCity(int $city): JsonResponse
    {
        $areas = Area::where('city_id', $city)
                     ->where('is_active', true)
                     ->orderBy('name')
                     ->get(['id', 'name', 'pincode']);

        return response()->json($areas);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Load all dropdown data needed for each step view.
     * Keeps controller slim — no raw queries in show().
     */
    private function getStepViewData(int $step, $user): array
    {
        return match ($step) {

            1 => [
                // No dropdowns — all static enum values
                'marital_statuses' => ['never_married', 'divorced', 'widowed', 'awaiting_divorce'],
                'body_types'       => ['slim', 'athletic', 'average', 'heavy'],
                'complexions'      => ['very_fair', 'fair', 'wheatish', 'dark'],
                'blood_groups'     => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
                'diet_options'     => ['vegetarian', 'non_vegetarian', 'eggetarian', 'vegan', 'jain'],
                'smoking_options'  => ['no', 'occasionally', 'yes'],
                'drinking_options' => ['no', 'occasionally', 'yes'],
            ],

            2 => [
                'religions'      => Religion::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
                'mother_tongues' => MotherTongue::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
                'languages'      => Language::where('is_active', true)->orderBy('name')->get(['id', 'name']),
                // castes, sub_castes, gotras, communities loaded via AJAX on religion selection
                // Pre-populate if editing (profile already has religion_id)
                'castes'         => $user->profile?->religion_id
                                    ? Caste::where('religion_id', $user->profile->religion_id)->where('is_active', true)->get(['id', 'name'])
                                    : collect(),
                'sub_castes'     => $user->profile?->caste_id
                                    ? SubCaste::where('caste_id', $user->profile->caste_id)->where('is_active', true)->get(['id', 'name'])
                                    : collect(),
                'gotras'         => $user->profile?->religion_id
                                    ? Gotra::where('religion_id', $user->profile->religion_id)->where('is_active', true)->get(['id', 'name'])
                                    : collect(),
                'communities'    => $user->profile?->religion_id
                                    ? Community::where('religion_id', $user->profile->religion_id)->where('is_active', true)->get(['id', 'name'])
                                    : collect(),
            ],

            3 => [
                'rashis'          => Rashi::where('is_active', true)->orderBy('sort_order')->get(['id', 'name', 'english_name']),
                'nakshatras'      => Nakshatra::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
                'manglik_options' => ['yes', 'no', 'partial', 'dont_know'],
            ],

            4 => [
                'education_levels'     => EducationLevel::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
                'professions'          => Profession::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
                'annual_income_ranges' => AnnualIncomeRange::where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('min_value')
                    ->get(['id', 'label', 'min_value', 'max_value', 'currency']),
            ],

            5 => [
                'countries'          => Country::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
                'family_types'       => ['nuclear', 'joint', 'extended'],
                'family_statuses'    => ['middle_class', 'upper_middle_class', 'affluent', 'rich'],
                'residency_statuses' => ['resident_indian', 'nri', 'oci', 'foreign_national'],
                // Pre-populate if editing
                'states'             => $user->profile?->country_id
                                        ? State::where('country_id', $user->profile->country_id)->where('is_active', true)->get(['id', 'name'])
                                        : collect(),
                'cities'             => $user->profile?->state_id
                                        ? City::where('state_id', $user->profile->state_id)->where('is_active', true)->get(['id', 'name'])
                                        : collect(),
                'areas'              => $user->profile?->city_id
                                        ? Area::where('city_id', $user->profile->city_id)->where('is_active', true)->get(['id', 'name', 'pincode'])
                                        : collect(),
            ],

            6 => [
                'religions'            => Religion::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
                'castes'               => Caste::where('is_active', true)->orderBy('name')->get(['id', 'name', 'religion_id']),
                'mother_tongues'       => MotherTongue::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
                'countries'            => Country::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
                'education_levels'     => EducationLevel::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
                'professions'          => Profession::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
                'annual_income_ranges' => AnnualIncomeRange::where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('min_value')
                    ->get(['id', 'label', 'min_value', 'max_value', 'currency']),
                'rashis'               => Rashi::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
                'marital_statuses'     => ['never_married', 'divorced', 'widowed', 'awaiting_divorce'],
                'diet_options'         => ['vegetarian', 'non_vegetarian', 'eggetarian', 'vegan', 'jain'],
                'smoking_options'      => ['no', 'occasionally', 'yes'],
                'drinking_options'     => ['no', 'occasionally', 'yes'],
                'manglik_options'      => ['yes','no','partial','any'],
                'residency_statuses'   => ['resident_indian', 'nri', 'oci', 'foreign_national', 'any'],
                'preference'           => $user->partnerPreference,
            ],

            7 => [
                'photos' => $user->photos,

                'photo_privacy_opts'   => ['all', 'accepted_interest', 'premium'],
                'contact_privacy_opts' => ['all', 'accepted_interest', 'premium'],
                'visibility_opts'      => ['everyone', 'registered', 'hidden'],

                'max_photos' => $this->photoLimitFor($user),
            ],

            default => [],
        };
    }

    /**
     * Resolve the typed FormRequest for each step and run validation.
     * Returns the validated data array.
     */
    private function resolveAndValidate(int $step, \Illuminate\Http\Request $request): array
    {
        // Each StepXRequest extends FormRequest and has its own rules()
        $formRequestClass = match ($step) {
            1 => Step1Request::class,
            2 => Step2Request::class,
            3 => Step3Request::class,
            4 => Step4Request::class,
            5 => Step5Request::class,
            6 => Step6Request::class,
            7 => Step7Request::class,
        };

        // Resolve the FormRequest through the IoC container so it goes through
        // the full authorization + validation lifecycle
        $formRequest = app($formRequestClass);

        return $formRequest->validated();
    }

    private function photoLimitFor($user): ?int
    {
        if ($user->activeSubscription?->isValid()) {
            $limit = (int) $user->activeSubscription->package->photo_gallery_limit;

            return $limit <= 0 ? null : $limit;
        }

        return 1;
    }
}
