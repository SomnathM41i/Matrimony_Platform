<?php

namespace App\Http\Requests\User\Setup;

use Illuminate\Foundation\Http\FormRequest;

class Step3Request extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        // All horoscope fields are optional — step is skippable
        return [
            'rashi_id'      => ['nullable', 'integer', 'exists:rashis,id'],
            'nakshatra_id'  => ['nullable', 'integer', 'exists:nakshatras,id'],
            'manglik_status'=> ['nullable', 'in:yes,no,partial,dont_know'],
            'birth_time'    => ['nullable', 'date_format:H:i'],
            'birth_place'   => ['nullable', 'string', 'max:100'],
        ];
    }
}