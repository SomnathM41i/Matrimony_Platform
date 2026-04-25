<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// FIX: Added 'languages_known' JSON column for additional languages
// beyond mother tongue — needed for search/matching.

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            // ── Personal ──────────────────────────────────────────────
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->text('about_me')->nullable();
            $table->enum('marital_status', [
                'never_married', 'divorced', 'widowed', 'awaiting_divorce'
            ])->nullable();
            $table->unsignedTinyInteger('no_of_children')->default(0);
            $table->enum('body_type', ['slim', 'average', 'athletic', 'heavy'])->nullable();
            $table->enum('complexion', ['very_fair', 'fair', 'wheatish', 'dark'])->nullable();
            $table->unsignedSmallInteger('height_cm')->nullable()->index();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->enum('diet', [
                'vegetarian', 'non_vegetarian', 'eggetarian', 'vegan', 'jain'
            ])->nullable();
            $table->enum('smoking', ['no', 'occasionally', 'yes'])->nullable();
            $table->enum('drinking', ['no', 'occasionally', 'yes'])->nullable();
            $table->boolean('is_differently_abled')->default(false);

            // ── Community & Religion ──────────────────────────────────
            $table->foreignId('religion_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('caste_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sub_caste_id')->nullable()->constrained('sub_castes')->nullOnDelete();
            $table->foreignId('gotra_id')->nullable()->constrained('gotras')->nullOnDelete();
            $table->foreignId('community_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('mother_tongue_id')->nullable()->constrained('mother_tongues')->nullOnDelete();

            // FIX: languages_known JSON for additional spoken languages
            $table->json('languages_known')->nullable();

            // ── Horoscope (Optional) ──────────────────────────────────
            $table->foreignId('rashi_id')->nullable()->constrained('rashis')->nullOnDelete();
            $table->foreignId('nakshatra_id')->nullable()->constrained('nakshatras')->nullOnDelete();
            $table->enum('manglik_status', ['yes', 'no', 'partial', 'dont_know'])->nullable();
            $table->time('birth_time')->nullable();
            $table->string('birth_place')->nullable();

            // ── Education & Career ────────────────────────────────────
            $table->foreignId('education_level_id')->nullable()->constrained()->nullOnDelete();
            $table->string('education_details')->nullable();
            $table->foreignId('profession_id')->nullable()->constrained()->nullOnDelete();
            $table->string('company_name')->nullable();
            $table->foreignId('annual_income_range_id')->nullable()->constrained()->nullOnDelete();

            // ── Location ──────────────────────────────────────────────
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('state_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete();
            $table->string('pincode', 20)->nullable();
            $table->string('citizenship')->nullable();
            $table->string('residency_status')->nullable();

            // ── Family ────────────────────────────────────────────────
            $table->enum('family_type', ['nuclear', 'joint'])->nullable();
            $table->enum('family_status', [
                'middle_class', 'upper_middle_class', 'rich', 'affluent'
            ])->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->unsignedTinyInteger('no_of_brothers')->default(0);
            $table->unsignedTinyInteger('no_of_sisters')->default(0);

            // ── Privacy / Visibility ──────────────────────────────────
            $table->enum('photo_privacy', [
                'all', 'accepted_interest', 'premium'
            ])->default('all');
            $table->enum('contact_privacy', [
                'all', 'accepted_interest', 'premium'
            ])->default('accepted_interest');
            $table->enum('profile_visibility', [
                'everyone', 'registered', 'hidden'
            ])->default('everyone');

            // ── Profile Completion ────────────────────────────────────
            $table->unsignedTinyInteger('completion_percentage')->default(0);

            $table->timestamps();

            // ── Indexes ───────────────────────────────────────────────
            $table->index(['religion_id', 'caste_id']);
            $table->index(['country_id', 'state_id', 'city_id']);
            $table->index(['marital_status', 'height_cm']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
