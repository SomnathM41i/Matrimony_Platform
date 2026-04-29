<?php

namespace App\Http\Requests\User\Setup;

use Illuminate\Foundation\Http\FormRequest;

class Step7Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Photos (optional but validated if present)
            'photos'   => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpeg,png,webp', 'max:5120'], // 5MB

            // Primary photo (optional)
            'primary_photo_id' => ['nullable', 'integer', 'exists:profile_photos,id'],

            // Privacy settings
            'photo_privacy' => ['required', 'in:all,matches_only,hidden'],
            'contact_privacy' => ['required', 'in:all,accepted_interest,premium_only'],
            'profile_visibility' => ['required', 'in:all,registered_users,hidden'],
        ];
    }

    public function messages(): array
    {
        return [
            'photos.max' => 'You can upload a maximum of 5 photos.',
            'photos.*.image' => 'Each file must be a valid image.',
            'photos.*.mimes' => 'Only JPG, PNG, WEBP formats are allowed.',
            'photos.*.max' => 'Each photo must be less than 5MB.',

            'photo_privacy.required' => 'Please select photo visibility.',
            'contact_privacy.required' => 'Please select contact visibility.',
            'profile_visibility.required' => 'Please select profile visibility.',
        ];
    }
}