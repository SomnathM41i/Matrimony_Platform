<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NEW TABLE: languages + profile_languages pivot
// Reason: 'languages_known' stored as JSON in user_profiles is not
// efficiently queryable for search/matching. A proper normalized lookup
// + pivot enables Admin-controlled language list and indexed search.

return new class extends Migration {
    public function up(): void
    {
        // Admin-controlled language list (like religions, castes, etc.)
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->nullable();     // ISO 639-1 e.g. 'hi', 'en'
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Pivot: user profile ↔ known languages
        Schema::create('profile_languages', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->primary(['user_id', 'language_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_languages');
        Schema::dropIfExists('languages');
    }
};
