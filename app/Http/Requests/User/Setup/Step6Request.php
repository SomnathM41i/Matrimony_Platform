<?php

namespace App\Http\Requests\User\Setup;

use Illuminate\Foundation\Http\FormRequest;

class Step6Request extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // Age range — both required, min must be < max
            'age_min'                   => ['required', 'integer', 'min:18', 'max:80', 'lt:age_max'],
            'age_max'                   => ['required', 'integer', 'min:18', 'max:80', 'gt:age_min'],

            // Height range — optional
            'height_min_cm'             => ['nullable', 'integer', 'min:100', 'max:250'],
            'height_max_cm'             => ['nullable', 'integer', 'min:100', 'max:250', 'gte:height_min_cm'],

            // Marital status — multi-select array
            'marital_status'            => ['nullable', 'array'],
            'marital_status.*'          => ['in:never_married,divorced,widowed,awaiting_divorce'],

            // Religion & caste — multi-select arrays
            'religion_ids'              => ['nullable', 'array'],
            'religion_ids.*'            => ['integer', 'exists:religions,id'],
            'caste_ids'                 => ['nullable', 'array'],
            'caste_ids.*'               => ['integer', 'exists:castes,id'],
            'sub_caste_ids'             => ['nullable', 'array'],
            'sub_caste_ids.*'           => ['integer', 'exists:sub_castes,id'],
            'mother_tongue_ids'         => ['nullable', 'array'],
            'mother_tongue_ids.*'       => ['integer', 'exists:mother_tongues,id'],
            'caste_no_bar'              => ['sometimes', 'boolean'],

            // Location preferences — multi-select
            'country_ids'               => ['nullable', 'array'],
            'country_ids.*'             => ['integer', 'exists:countries,id'],
            'state_ids'                 => ['nullable', 'array'],
            'state_ids.*'               => ['integer', 'exists:states,id'],
            'city_ids'                  => ['nullable', 'array'],
            'city_ids.*'                => ['integer', 'exists:cities,id'],
            'residency_status_pref'     => ['nullable', 'in:resident_indian,nri,oci,foreign_national,any'],

            // Education & career preferences
            'education_level_ids'       => ['nullable', 'array'],
            'education_level_ids.*'     => ['integer', 'exists:education_levels,id'],
            'profession_ids'            => ['nullable', 'array'],
            'profession_ids.*'          => ['integer', 'exists:professions,id'],
            'annual_income_range_id_min'=> ['nullable', 'integer', 'exists:annual_income_ranges,id'],

            // Lifestyle preferences
            'diet'                      => ['nullable', 'array'],
            'diet.*'                    => ['in:vegetarian,non_vegetarian,eggetarian,vegan,jain'],
            'smoking'                   => ['nullable', 'array'],
            'smoking.*'                 => ['in:no,occasionally,yes'],
            'drinking'                  => ['nullable', 'array'],
            'drinking.*'                => ['in:no,occasionally,yes'],

            // Horoscope preferences
            'rashi_ids'                 => ['nullable', 'array'],
            'rashi_ids.*'               => ['integer', 'exists:rashis,id'],
            'manglik_pref'              => ['nullable', 'in:yes,no,partial,any'],

            // Free text
            'about_partner'             => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'age_min.required' => 'Please specify the minimum preferred age.',
            'age_max.required' => 'Please specify the maximum preferred age.',
            'age_min.lt'       => 'Minimum age must be less than maximum age.',
            'age_max.gt'       => 'Maximum age must be greater than minimum age.',
        ];
    }
}