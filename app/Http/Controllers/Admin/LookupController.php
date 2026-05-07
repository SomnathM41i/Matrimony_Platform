<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class LookupController extends Controller
{

    private function map(): array
    {
        return [
            'religions'            => \App\Models\Religion::class,
            'castes'               => \App\Models\Caste::class,
            'sub-castes'           => \App\Models\SubCaste::class,
            'gotras'               => \App\Models\Gotra::class,
            'communities'          => \App\Models\Community::class,
            'mother-tongues'       => \App\Models\MotherTongue::class,
            'rashis'               => \App\Models\Rashi::class,
            'nakshatras'           => \App\Models\Nakshatra::class,
            'education-levels'     => \App\Models\EducationLevel::class,
            'professions'          => \App\Models\Profession::class,
            'annual-income-ranges' => \App\Models\AnnualIncomeRange::class,
            'countries'            => \App\Models\Country::class,
            'states'               => \App\Models\State::class,
            'cities'               => \App\Models\City::class,
            'areas'                => \App\Models\Area::class,
        ];
    }

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

        $records = $type === 'annual-income-ranges'
            ? $model::orderBy('sort_order')->orderBy('min_value')->paginate(15)
            : $model::latest()->paginate(15);

        return view('admin.lookups.index', compact('records', 'type'));
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        $model = $this->resolve($type);

        $data = $request->validate($this->validationRules($type));

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

    public function import(Request $request, string $type): RedirectResponse
    {
        $this->resolve($type); // validates the type

        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        return back()->with('success', ucfirst($type) . ' import completed.');
    }

    public function export(string $type): Response
    {
        $model = $this->resolve($type);

        $records = $type === 'annual-income-ranges'
            ? $model::orderBy('sort_order')->orderBy('min_value')->get()
            : $model::orderBy('id')->get();

        $csv = $records->map(fn ($r) => implode(',', array_map(
            fn ($v) => '"' . str_replace('"', '""', (string) $v) . '"',
            $r->toArray()
        )))->prepend(implode(',', array_keys($records->first()?->toArray() ?? [])))->implode("\n");

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $type . '.csv"',
        ]);
    }

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
                $rules['label']      = ['required', 'string', 'max:150'];
                $rules['min_value']  = ['required', 'integer', 'min:0'];
                $rules['max_value']  = ['nullable', 'integer', 'min:0', 'gte:min_value'];
                $rules['currency']   = ['nullable', 'string', 'max:5'];
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
