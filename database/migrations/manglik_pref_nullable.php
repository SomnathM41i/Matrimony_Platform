<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX (Image error): manglik_pref column was NOT NULL with DEFAULT 'any',
 * but no ->nullable() was set. When a user submits the Step 6 form without
 * selecting a Manglik preference, the validated value is null, which MySQL
 * rejected with:
 *   SQLSTATE[23000]: Integrity constraint violation: 1048
 *   Column 'manglik_pref' cannot be null
 *
 * Fix: make the column nullable so null is a valid stored value,
 * while keeping the default of 'any' for existing rows.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partner_preferences', function (Blueprint $table) {
            $table->enum('manglik_pref', ['yes', 'no', 'partial', 'any'])
                  ->nullable()          // ← allows null when user picks "No Preference"
                  ->default('any')
                  ->change();
        });
    }

    public function down(): void
    {
        // First set any null values back to 'any' to avoid NOT NULL violation on rollback
        \DB::table('partner_preferences')->whereNull('manglik_pref')->update(['manglik_pref' => 'any']);

        Schema::table('partner_preferences', function (Blueprint $table) {
            $table->enum('manglik_pref', ['yes', 'no', 'partial', 'any'])
                  ->nullable(false)
                  ->default('any')
                  ->change();
        });
    }
};