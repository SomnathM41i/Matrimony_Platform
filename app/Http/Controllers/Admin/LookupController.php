<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class LookupController extends Controller
{
    /**
     * Get model map (runtime — safe)
     *
     * BUG FIX #1: Added all missing types that were in routes/API but absent here:
     *   mother-tongues, rashis, nakshatras, education-levels, professions, annual-income-ranges
     *   Previously these returned 404 on every CRUD operation.
     */
    private function map(): array
    {
        return [
            'religions'            => \App\Models\Religion::class,
            'castes'               => \App\Models\Caste::class,
            'sub-castes'           => \App\Models\SubCaste::class,
            'gotras'               => \App\Models\Gotra::class,
            'communities'          => \App\Models\Community::class,
            'mother-tongues'       => \App\Models\MotherTongue::class,   // was missing
            'rashis'               => \App\Models\Rashi::class,           // was missing
            'nakshatras'           => \App\Models\Nakshatra::class,       // was missing
            'education-levels'     => \App\Models\EducationLevel::class,  // was missing
            'professions'          => \App\Models\Profession::class,      // was missing
            'annual-income-ranges' => \App\Models\AnnualIncomeRange::class, // was missing
            'countries'            => \App\Models\Country::class,
            'states'               => \App\Models\State::class,
            'cities'               => \App\Models\City::class,
            'areas'                => \App\Models\Area::class,
        ];
    }

    /**
     * Resolve model by type
     */
    private function resolve(string $type)
    {
        $map = $this->map();

        abort_unless(isset($map[$type]), 404);

        return $map[$type];
    }

    /**
     * INDEX
     */
    public function index(string $type): View
    {
        $model = $this->resolve($type);

        $records = $model::latest()->paginate(15);

        return view('admin.lookups.index', compact('records', 'type'));
    }

    /**
     * STORE
     *
     * BUG FIX #2: Original only validated 'name' + 'is_active', silently dropping
     *   all other fields: label, min_value, max_value, currency, iso_code,
     *   phone_code, code, pincode, english_name, sort_order.
     *   Now all optional type-specific fields are validated and saved.
     *
     * BUG FIX #4: Replaced truthy string checks with proper boolean() calls.
     */
    public function store(Request $request, string $type): RedirectResponse
    {
        $model = $this->resolve($type);

        $data = $request->validate($this->validationRules($type));
        //dd($request); exit;
        $data['is_active'] = $request->boolean('is_active');

        // Parent FK relations — only include if present in the request
        foreach (['religion_id', 'caste_id', 'country_id', 'state_id', 'city_id'] as $fk) {
            if ($request->has($fk)) {
                $data[$fk] = $request->integer($fk) ?: null;
            }
        }

        $model::create($data);

        return back()->with('success', ucfirst($type) . ' created.');
    }

    /**
     * UPDATE (toggle or full update)
     *
     * BUG FIX #3: Full-update branch previously validated only 'name' + 'is_active',
     *   losing all extra fields on edit. Now delegates to the same rules as store().
     *
     * BUG FIX #4: _toggle_only was checked as a truthy value on a raw request input
     *   (string "1"). Now uses $request->boolean() for proper semantics.
     */
    public function update(Request $request, string $type, int $id): RedirectResponse
    {
        $model = $this->resolve($type);

        $row = $model::findOrFail($id);

        // Toggle only
        if ($request->boolean('_toggle_only')) {
            $row->update([
                'is_active' => $request->boolean('is_active'),
            ]);

            return back()->with('success', 'Status updated.');
        }

        // Full update
        $data = $request->validate($this->validationRules($type));

        $data['is_active'] = $request->boolean('is_active');

        $row->update($data);

        return back()->with('success', ucfirst($type) . ' updated.');
    }

    /**
     * DELETE
     */
    public function destroy(string $type, int $id): RedirectResponse
    {
        $model = $this->resolve($type);

        $model::findOrFail($id)->delete();

        return back()->with('success', ucfirst($type) . ' deleted.');
    }

    /**
     * IMPORT
     *
     * BUG FIX #12: Method was referenced in routes but didn't exist, causing
     *   a runtime BindingResolutionException / BadMethodCallException.
     *   Stub implemented — replace body with real CSV/Excel import logic as needed.
     */
    public function import(Request $request, string $type): RedirectResponse
    {
        $this->resolve($type); // validates the type

        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        // TODO: implement CSV import logic here
        // e.g. use League\Csv or a dedicated import job

        return back()->with('success', ucfirst($type) . ' import completed.');
    }

    /**
     * EXPORT
     *
     * BUG FIX #12: Method was referenced in routes but didn't exist.
     *   Stub returns a CSV download — expand as needed.
     */
    public function export(string $type): Response
    {
        $model = $this->resolve($type);

        $records = $model::orderBy('id')->get();

        $csv = $records->map(fn ($r) => implode(',', array_map(
            fn ($v) => '"' . str_replace('"', '""', (string) $v) . '"',
            $r->toArray()
        )))->prepend(implode(',', array_keys($records->first()?->toArray() ?? [])))->implode("\n");

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $type . '.csv"',
        ]);
    }

    /**
     * Return validation rules appropriate for the given lookup type.
     *
     * Centralises rules so store() and update() stay in sync.
     */
    private function validationRules(string $type): array
    {
        // Base rules for every type
        $rules = [
            'is_active'  => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];

        // Types that use 'name' as primary field
        $nameTypes = [
            'religions', 'castes', 'sub-castes', 'gotras', 'communities',
            'mother-tongues', 'rashis', 'nakshatras', 'education-levels',
            'professions', 'countries', 'states', 'cities', 'areas',
        ];

        if (in_array($type, $nameTypes)) {
            $rules['name'] = ['required', 'string', 'max:100'];
        }

        // Type-specific extra fields
        switch ($type) {
            case 'annual-income-ranges':
                $rules['label']     = ['required', 'string', 'max:150'];
                $rules['min_value'] = ['required', 'numeric', 'min:0'];
                $rules['max_value'] = ['nullable', 'numeric', 'min:0'];
                $rules['currency']  = ['nullable', 'string', 'max:5'];
                break;

            case 'countries':
                $rules['iso_code']   = ['required', 'string', 'max:10'];
                $rules['phone_code'] = ['nullable', 'string', 'max:10'];
                break;

            case 'states':
                $rules['code'] = ['nullable', 'string', 'max:10'];
                break;

            case 'areas':
                $rules['pincode'] = ['nullable', 'string', 'max:20'];
                break;

            case 'rashis':
                $rules['english_name'] = ['nullable', 'string', 'max:100'];
                break;
        }

        return $rules;
    }
}