<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NEW TABLE: saved_searches
// Reason: Prompt requires Advanced Search (filter + keyword).
// Users should be able to save searches and get notified of new matches.
// Also feeds search analytics for the Admin Panel.

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saved_searches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('name')->nullable();                 // user-given label
            $table->enum('search_type', ['filter', 'keyword'])->default('filter');

            // Serialised filter state — mirrors the Advanced Search form
            $table->json('filters')->nullable();                // age, height, location, etc.
            $table->string('keyword')->nullable();              // name / city / profession

            $table->boolean('notify_new_matches')->default(false);  // email/push on new match
            $table->timestamp('last_run_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'search_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_searches');
    }
};
