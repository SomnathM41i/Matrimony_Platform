<?php

namespace App\Http\Requests\User\Setup;

use Illuminate\Foundation\Http\FormRequest;

class Step5Request extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // Location — country/state/city required; area optional
            'country_id'        => ['required', 'integer', 'exists:countries,id'],
            'state_id'          => ['required', 'integer', 'exists:states,id'],
            'city_id'           => ['required', 'integer', 'exists:cities,id'],
            'area_id'           => ['nullable', 'integer', 'exists:areas,id'],
            'pincode'           => ['nullable', 'string', 'max:10'],
            'citizenship'       => ['nullable', 'string', 'max:100'],
            'residency_status'  => ['required', 'in:resident_indian,nri,oci,foreign_national'],

            // Family
            // FIX #2: Removed 'extended' — DB enum only allows 'nuclear' and 'joint'
            'family_type'       => ['required', 'in:nuclear,joint'],
            'family_status'     => ['nullable', 'in:middle_class,upper_middle_class,affluent,rich'],
            'father_occupation' => ['nullable', 'string', 'max:150'],
            'mother_occupation' => ['nullable', 'string', 'max:150'],
            'no_of_brothers'    => ['nullable', 'integer', 'min:0', 'max:10'],
            'no_of_sisters'     => ['nullable', 'integer', 'min:0', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'country_id.required'      => 'Please select your country.',
            'state_id.required'        => 'Please select your state.',
            'city_id.required'         => 'Please select your city.',
            'residency_status.required'=> 'Please select your residency status.',
            'family_type.required'     => 'Please select your family type.',
        ];
    }
}