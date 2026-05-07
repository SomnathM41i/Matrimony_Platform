<?php

namespace App\Http\Requests\User\Setup;

use Illuminate\Foundation\Http\FormRequest;

class Step3Request extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('birth_time')) {
            return;
        }

        $birthTime = (string) $this->input('birth_time');

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $birthTime)) {
            $this->merge(['birth_time' => substr($birthTime, 0, 5)]);
        }
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
