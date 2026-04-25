<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * LookupApiController
 *
 * Serves lightweight JSON lists for cascade dropdowns in the Lookup admin UI.
 * Routes: GET /admin/api/lookups/{type}?parent_id={id}
 */
class LookupApiController extends Controller
{
    /**
     * Maps URL slug → [Model class, parent FK column (if any)]
     */
    private array $map = [
        'religions'            => [\App\Models\Religion::class,          null],
        'castes'               => [\App\Models\Caste::class,             'religion_id'],
        'sub-castes'           => [\App\Models\SubCaste::class,          'caste_id'],
        'gotras'               => [\App\Models\Gotra::class,             'religion_id'],
        'communities'          => [\App\Models\Community::class,         'religion_id'],
        'mother-tongues'       => [\App\Models\MotherTongue::class,      null],
        'rashis'               => [\App\Models\Rashi::class,             null],
        'nakshatras'           => [\App\Models\Nakshatra::class,         null],
        'education-levels'     => [\App\Models\EducationLevel::class,    null],
        'professions'          => [\App\Models\Profession::class,        null],
        'annual-income-ranges' => [\App\Models\AnnualIncomeRange::class, null],
        'countries'            => [\App\Models\Country::class,           null],
        'states'               => [\App\Models\State::class,             'country_id'],
        'cities'               => [\App\Models\City::class,              'state_id'],
        'areas'                => [\App\Models\Area::class,              'city_id'],
    ];

    /**
     * Return a flat JSON list for use in <select> dropdowns.
     * Optionally filtered by parent_id.
     */
    public function index(Request $request, string $type): JsonResponse
    {
        if (! isset($this->map[$type])) {
            return response()->json(['error' => 'Unknown lookup type'], 404);
        }

        [$modelClass, $parentFk] = $this->map[$type];

        $query = $modelClass::where('is_active', true);

        // Filter by parent if provided and applicable
        if ($parentFk && $request->filled('parent_id')) {
            $query->where($parentFk, $request->integer('parent_id'));
        }

        // Determine display column (income ranges use 'label', rest use 'name')
        $displayCol = $type === 'annual-income-ranges' ? 'label' : 'name';

        $records = $query
            ->orderBy('sort_order', 'asc')
            ->orderBy($displayCol, 'asc')
            ->get(['id', $displayCol])
            ->map(fn ($r) => ['id' => $r->id, 'name' => $r->{$displayCol}]);

        return response()->json($records);
    }
}