<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// FIX: Added $table->timestamps() — original had only 'computed_at',
// making it impossible to track when the record was created vs last recomputed.

return new class extends Migration {
    public function up(): void
    {
        Schema::create('compatibility_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('match_user_id')->constrained('users')->cascadeOnDelete();

            // Granular scores (0–100)
            $table->unsignedTinyInteger('overall_score')->default(0);
            $table->unsignedTinyInteger('preference_score')->default(0);
            $table->unsignedTinyInteger('religious_score')->default(0);
            $table->unsignedTinyInteger('lifestyle_score')->default(0);
            $table->unsignedTinyInteger('location_score')->default(0);
            $table->unsignedTinyInteger('education_score')->default(0);  // added

            $table->timestamp('computed_at')->useCurrent()->useCurrentOnUpdate(); // auto-updates
            $table->timestamps(); // FIX: was missing

            $table->unique(['user_id', 'match_user_id']);
            $table->index(['user_id', 'overall_score']);
            $table->index(['match_user_id', 'overall_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compatibility_scores');
    }
};
