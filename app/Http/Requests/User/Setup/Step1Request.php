<?php

namespace App\Http\Requests\User\Setup;

use Illuminate\Foundation\Http\FormRequest;

class Step1Request extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'about_me'             => ['required', 'string', 'min:30', 'max:1000'],
            'marital_status' => ['required', 'in:never_married,divorced,widowed,awaiting_divorce'],
            'no_of_children'       => ['nullable', 'integer', 'min:0', 'max:10'],
            'height_cm'            => ['required', 'integer', 'min:100', 'max:250'],
            'weight_kg'            => ['nullable', 'numeric', 'min:30', 'max:200'],
            'body_type'            => ['nullable', 'in:slim,athletic,average,heavy'],
            'complexion'           => ['nullable', 'in:very_fair,fair,wheatish,dark'],
            'blood_group'          => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'diet'                 => ['required', 'in:vegetarian,non_vegetarian,eggetarian,vegan,jain'],
            'smoking'              => ['required', 'in:no,occasionally,yes'],
            'drinking'             => ['required', 'in:no,occasionally,yes'],
            'is_differently_abled' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'about_me.min'      => 'Please write at least 30 characters about yourself.',
            'height_cm.required'=> 'Height is required.',
            'height_cm.min'     => 'Please enter a valid height.',
            'marital_status.required' => 'Please select your marital status.',
        ];
    }
}