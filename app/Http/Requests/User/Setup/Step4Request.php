<?php

namespace App\Http\Requests\User\Setup;

use Illuminate\Foundation\Http\FormRequest;

class Step4Request extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'education_level_id'    => ['required', 'integer', 'exists:education_levels,id'],
            'education_details'     => ['nullable', 'string', 'max:255'],
            'profession_id'         => ['required', 'integer', 'exists:professions,id'],
            'company_name'          => ['nullable', 'string', 'max:150'],
            'annual_income_range_id'=> ['required', 'integer', 'exists:annual_income_ranges,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'education_level_id.required'    => 'Please select your highest education level.',
            'profession_id.required'         => 'Please select your profession.',
            'annual_income_range_id.required'=> 'Please select your annual income range.',
        ];
    }
}