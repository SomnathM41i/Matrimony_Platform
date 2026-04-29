<?php

namespace App\Http\Requests\User\Setup;

use Illuminate\Foundation\Http\FormRequest;

class Step2Request extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'religion_id'      => ['required', 'integer', 'exists:religions,id'],
            'caste_id'         => ['nullable', 'integer', 'exists:castes,id'],
            'sub_caste_id'     => ['nullable', 'integer', 'exists:sub_castes,id'],
            'gotra_id'         => ['nullable', 'integer', 'exists:gotras,id'],
            'community_id'     => ['nullable', 'integer', 'exists:communities,id'],
            'mother_tongue_id' => ['required', 'integer', 'exists:mother_tongues,id'],
            'languages_known'  => ['nullable', 'array'],
            'languages_known.*'=> ['integer', 'exists:languages,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'religion_id.required'      => 'Please select your religion.',
            'mother_tongue_id.required' => 'Please select your mother tongue.',
        ];
    }
}