<?php

namespace App\Services\User;

use App\Models\PartnerPreference;
use App\Models\ProfilePhoto;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileSetupService
{
    // =========================================================================
    // STEP ROUTER — delegates to the correct save method
    // =========================================================================

    public function saveStep(int $step, User $user, array $data): void
    {
        match ($step) {
            1 => $this->saveStep1($user, $data),
            2 => $this->saveStep2($user, $data),
            3 => $this->saveStep3($user, $data),
            4 => $this->saveStep4($user, $data),
            5 => $this->saveStep5($user, $data),
            6 => $this->saveStep6($user, $data),
            7 => $this->saveStep7($user, $data),
        };
    }

    // =========================================================================
    // STEP 1 — Basic Info
    // Saves to: user_profiles
    // Fields: about_me, marital_status, no_of_children, body_type, complexion,
    //         height_cm, weight_kg, blood_group, diet, smoking, drinking,
    //         is_differently_abled
    // =========================================================================

    private function saveStep1(User $user, array $data): void
    {
        $user->profile->update([
            'about_me'            => $data['about_me'],
            'marital_status'      => $data['marital_status'],
            'no_of_children'      => $data['no_of_children'] ?? null,
            'body_type'           => $data['body_type'] ?? null,
            'complexion'          => $data['complexion'] ?? null,
            'height_cm'           => $data['height_cm'],
            'weight_kg'           => $data['weight_kg'] ?? null,
            'blood_group'         => $data['blood_group'] ?? null,
            'diet'                => $data['diet'],
            'smoking'             => $data['smoking'],
            'drinking'            => $data['drinking'],
            'is_differently_abled'=> $data['is_differently_abled'] ?? false,
        ]);
    }

    // =========================================================================
    // STEP 2 — Religion & Community
    // Saves to: user_profiles
    // Fields: religion_id, caste_id, sub_caste_id, gotra_id, community_id,
    //         mother_tongue_id, languages_known
    // =========================================================================

    private function saveStep2(User $user, array $data): void
    {
        $user->profile->update([
            'religion_id'     => $data['religion_id'],
            'caste_id'        => $data['caste_id'] ?? null,
            'sub_caste_id'    => $data['sub_caste_id'] ?? null,
            'gotra_id'        => $data['gotra_id'] ?? null,
            'community_id'    => $data['community_id'] ?? null,
            'mother_tongue_id'=> $data['mother_tongue_id'],
            'languages_known' => $data['languages_known'] ?? [],
        ]);
    }

    // =========================================================================
    // STEP 3 — Horoscope (Optional / Skippable)
    // Saves to: user_profiles
    // Fields: rashi_id, nakshatra_id, manglik_status, birth_time, birth_place
    // =========================================================================

    private function saveStep3(User $user, array $data): void
    {
        $user->profile->update([
            'rashi_id'      => $data['rashi_id'] ?? null,
            'nakshatra_id'  => $data['nakshatra_id'] ?? null,
            'manglik_status'=> $data['manglik_status'] ?? null,
            'birth_time'    => $data['birth_time'] ?? null,
            'birth_place'   => $data['birth_place'] ?? null,
        ]);
    }

    // =========================================================================
    // STEP 4 — Education & Career
    // Saves to: user_profiles
    // Fields: education_level_id, education_details, profession_id,
    //         company_name, annual_income_range_id
    // =========================================================================

    private function saveStep4(User $user, array $data): void
    {
        $user->profile->update([
            'education_level_id'   => $data['education_level_id'],
            'education_details'    => $data['education_details'] ?? null,
            'profession_id'        => $data['profession_id'],
            'company_name'         => $data['company_name'] ?? null,
            'annual_income_range_id'=> $data['annual_income_range_id'],
        ]);
    }

    // =========================================================================
    // STEP 5 — Location & Family
    // Saves to: user_profiles
    // Fields: country_id, state_id, city_id, area_id, pincode,
    //         citizenship, residency_status,
    //         family_type, family_status, father_occupation,
    //         mother_occupation, no_of_brothers, no_of_sisters
    // =========================================================================

    private function saveStep5(User $user, array $data): void
    {
        $user->profile->update([
            'country_id'        => $data['country_id'],
            'state_id'          => $data['state_id'],
            'city_id'           => $data['city_id'],
            'area_id'           => $data['area_id'] ?? null,
            'pincode'           => $data['pincode'] ?? null,
            'citizenship'       => $data['citizenship'] ?? null,
            'residency_status'  => $data['residency_status'],
            'family_type'       => $data['family_type'],
            'family_status'     => $data['family_status'] ?? null,
            'father_occupation' => $data['father_occupation'] ?? null,
            'mother_occupation' => $data['mother_occupation'] ?? null,
            'no_of_brothers'    => $data['no_of_brothers'] ?? 0,
            'no_of_sisters'     => $data['no_of_sisters'] ?? 0,
        ]);
    }

    // =========================================================================
    // STEP 6 — Partner Preferences
    // Saves to: partner_preferences (updateOrCreate)
    // All multi-select fields are stored as JSON arrays in the model
    // =========================================================================

    private function saveStep6(User $user, array $data): void
    {
        PartnerPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'age_min'                  => $data['age_min'],
                'age_max'                  => $data['age_max'],
                'height_min_cm'            => $data['height_min_cm'] ?? null,
                'height_max_cm'            => $data['height_max_cm'] ?? null,
                'marital_status'           => $data['marital_status'] ?? [],
                'religion_ids'             => $data['religion_ids'] ?? [],
                'caste_ids'                => $data['caste_ids'] ?? [],
                'sub_caste_ids'            => $data['sub_caste_ids'] ?? [],
                'mother_tongue_ids'        => $data['mother_tongue_ids'] ?? [],
                'caste_no_bar'             => $data['caste_no_bar'] ?? false,
                'country_ids'              => $data['country_ids'] ?? [],
                'state_ids'                => $data['state_ids'] ?? [],
                'city_ids'                 => $data['city_ids'] ?? [],
                'residency_status_pref'    => $data['residency_status_pref'] ?? null,
                'education_level_ids'      => $data['education_level_ids'] ?? [],
                'profession_ids'           => $data['profession_ids'] ?? [],
                'annual_income_range_id_min'=> $data['annual_income_range_id_min'] ?? null,
                'diet'                     => $data['diet'] ?? [],
                'smoking'                  => $data['smoking'] ?? [],
                'drinking'                 => $data['drinking'] ?? [],
                'rashi_ids'                => $data['rashi_ids'] ?? [],
                'manglik_pref'             => $data['manglik_pref'] ?? null,
                'about_partner'            => $data['about_partner'] ?? null,
            ]
        );
    }

    // =========================================================================
    // STEP 7 — Photos & Privacy
    // Saves to: profile_photos + user_profiles (privacy fields)
    // Handles: upload, thumbnail generation, primary photo setting,
    //          photo_privacy, contact_privacy, profile_visibility
    // =========================================================================

    private function saveStep7(User $user, array $data): void
    {
        // ── Handle photo uploads ──────────────────────────────────────────
        if (!empty($data['photos'])) {
            $existingCount = $user->photos()->count();
            $maxPhotos     = $this->photoLimitFor($user);

            foreach ($data['photos'] as $index => $photoFile) {
                if ($maxPhotos !== null && $existingCount >= $maxPhotos) {
                    break; // Enforce admin-defined limit
                }

                // Store original in storage/app/public/profiles/{user_id}/
                $path = $photoFile->store("profiles/{$user->id}", 'public');

                // Generate thumbnail (200x200 crop)
                $thumbnailPath = $this->generateThumbnail($photoFile, $user->id, $path);

                // First photo uploaded becomes primary if none set
                $isPrimary = ($existingCount === 0 && $index === 0)
                             && !$user->primaryPhoto()->exists();

                ProfilePhoto::create([
                    'user_id'       => $user->id,
                    'path'          => $path,
                    'thumbnail_path'=> $thumbnailPath,
                    'is_primary'    => $isPrimary,
                    'is_approved'   => false, // Admin must approve
                    'sort_order'    => $existingCount + $index + 1,
                ]);

                $existingCount++;
            }
        }

        // ── Handle primary photo change ───────────────────────────────────
        if (!empty($data['primary_photo_id'])) {
            // Reset all → set chosen one
            $user->photos()->update(['is_primary' => false]);
            $user->photos()->where('id', $data['primary_photo_id'])->update(['is_primary' => true]);
        }

        // ── Privacy settings ──────────────────────────────────────────────
        $user->profile->update([
            'photo_privacy'      => $data['photo_privacy'],
            'contact_privacy'    => $data['contact_privacy'],
            'profile_visibility' => $data['profile_visibility'],
        ]);
    }

    private function photoLimitFor(User $user): ?int
    {
        $user->loadMissing('activeSubscription.package');

        if ($user->activeSubscription?->isValid()) {
            $limit = (int) $user->activeSubscription->package->photo_gallery_limit;

            return $limit <= 0 ? null : $limit;
        }

        return 1;
    }

    // =========================================================================
    // HELPER — Thumbnail Generation
    // Uses Intervention Image. If not installed, falls back to null.
    // =========================================================================

    private function generateThumbnail($file, int $userId, string $originalPath): ?string
    {
        try {
            $thumbnailPath = "profiles/{$userId}/thumbs/" . basename($originalPath);

            $image = Image::make($file)
                          ->fit(200, 200)
                          ->encode();

            Storage::disk('public')->put($thumbnailPath, $image);

            return $thumbnailPath;
        } catch (\Throwable $e) {
            // If Intervention Image not installed, skip thumbnail silently
            return null;
        }
    }

    // =========================================================================
    // HELPER — Track which step the user last completed
    // Used in show() to prevent skipping ahead
    // =========================================================================

    public function getCompletedStep(User $user): int
    {
        $profile = $user->profile;

        if (!$profile) return 0;

        // Work backwards through required fields per step
        if ($user->photos()->exists() || $profile->photo_privacy) return 7;
        if ($user->partnerPreference) return 6;
        if ($profile->country_id) return 5;
        if ($profile->education_level_id) return 4;
        if ($profile->rashi_id || $profile->manglik_status) return 3;
        if ($profile->religion_id) return 2;
        if ($profile->about_me || $profile->height_cm) return 1;

        return 0;
    }
}
