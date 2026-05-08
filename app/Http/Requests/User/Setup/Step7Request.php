<?php

namespace App\Http\Requests\User\Setup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Step7Request extends FormRequest
{
    // FIX #12: Was returning true unconditionally — must check authentication
    public function authorize(): bool
    {
        return auth()->check();
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

        // FIX #4: Store remaining count on the instance so messages() can use it dynamically
        $this->remainingPhotos = $remaining;

        return [
            // Photos (optional but validated if present)
            'photos'   => array_filter(['nullable', 'array', $remaining !== null ? 'max:' . $remaining : null]),
            'photos.*' => ['image', 'mimes:jpeg,png,webp', 'max:5120'], // 5MB per photo

            // Primary photo (optional)
            'primary_photo_id' => [
                'nullable',
                'integer',
                Rule::exists('profile_photos', 'id')->where('user_id', $this->user()?->id),
            ],

            // Privacy settings
            'photo_privacy'      => ['required', 'in:all,accepted_interest,premium'],
            'contact_privacy'    => ['required', 'in:all,accepted_interest,premium'],
            'profile_visibility' => ['required', 'in:everyone,registered,hidden'],
        ];
    }

    public function messages(): array
    {
        // FIX #4: Build the photos.max message dynamically using the actual computed limit,
        // instead of the hardcoded "5" which was wrong for free users (limit = 1) and
        // for premium plans with different limits.
        $limit = isset($this->remainingPhotos) && $this->remainingPhotos !== null
            ? $this->remainingPhotos
            : 'your plan\'s';

        return [
            'photos.max'              => "You can upload a maximum of {$limit} more photo(s) on your current plan.",
            'photos.*.image'          => 'Each file must be a valid image.',
            'photos.*.mimes'          => 'Only JPG, PNG, WEBP formats are allowed.',
            'photos.*.max'            => 'Each photo must be less than 5MB.',
            'photo_privacy.required'  => 'Please select photo visibility.',
            'contact_privacy.required'=> 'Please select contact visibility.',
            'profile_visibility.required' => 'Please select profile visibility.',
        ];
    }
}