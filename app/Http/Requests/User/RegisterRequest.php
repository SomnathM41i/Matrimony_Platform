<?php

namespace App\Http\Requests\User;

// use App\Http\Requests\User\RegisterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Guest access; auth check is handled by middleware
    }

    public function rules(): array
    {
        return [
            'first_name'    => ['required', 'string', 'max:60', 'regex:/^[\pL\s\-]+$/u'],
            'last_name'     => ['required', 'string', 'max:60', 'regex:/^[\pL\s\-]+$/u'],
            'email'         => ['required', 'email:rfc,dns', 'max:180', 'unique:users,email'],
            // FIX #13: Added regex to enforce valid phone number format (7–15 digits, optional leading +)
            'phone'         => ['required', 'string', 'max:20', 'regex:/^\+?[0-9]{7,15}$/', 'unique:users,phone'],
            'gender'        => ['required', 'in:male,female'],
            'date_of_birth' => ['required', 'date', 'before:'.now()->subYears(18)->toDateString(), 'after:'.now()->subYears(80)->toDateString()],
            // FIX #6: Increased minimum password length from 4 to 8 characters
            'password'      => ['required', 'confirmed', Password::min(8)->letters()->numbers()->mixedCase()],
            'terms'         => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.regex'        => 'First name may only contain letters, spaces, and hyphens.',
            'last_name.regex'         => 'Last name may only contain letters, spaces, and hyphens.',
            'email.unique'            => 'An account with this email already exists.',
            'phone.unique'            => 'This phone number is already registered.',
            // FIX #13: Message for phone format validation
            'phone.regex'             => 'Please enter a valid phone number (7–15 digits, optionally starting with +).',
            'date_of_birth.before'    => 'You must be at least 18 years old to register.',
            'date_of_birth.after'     => 'Please enter a valid date of birth.',
            'password.confirmed'      => 'Password confirmation does not match.',
            // FIX #6: Clearer message for stronger password requirement
            'password.min'            => 'Password must be at least 8 characters long.',
            'terms.accepted'          => 'You must accept the Terms & Conditions to register.',
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name'    => 'first name',
            'last_name'     => 'last name',
            'date_of_birth' => 'date of birth',
        ];
    }
}