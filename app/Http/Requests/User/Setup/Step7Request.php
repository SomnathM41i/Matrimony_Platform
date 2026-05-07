<?php

namespace App\Http\Requests\User\Setup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Step7Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user()?->loadMissing('activeSubscription.package', 'photos');
        $maxPhotos = $user?->activeSubscription?->isValid()
            ? (int) $user->activeSubscription->package->photo_gallery_limit
            : 1;
        $remaining = $maxPhotos <= 0
            ? null
            : max(0, $maxPhotos - (int) $user?->photos()->count());

        return [
            // Photos (optional but validated if present)
            'photos'   => array_filter(['nullable', 'array', $remaining !== null ? 'max:' . $remaining : null]),
            'photos.*' => ['image', 'mimes:jpeg,png,webp', 'max:5120'], // 5MB

            // Primary photo (optional)
            'primary_photo_id' => [
                'nullable',
                'integer',
                Rule::exists('profile_photos', 'id')->where('user_id', $this->user()?->id),
            ],

            // Privacy settings
            'photo_privacy' => ['required', 'in:all,accepted_interest,premium'],
            'contact_privacy' => ['required', 'in:all,accepted_interest,premium'],
            'profile_visibility' => ['required', 'in:everyone,registered,hidden'],
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
