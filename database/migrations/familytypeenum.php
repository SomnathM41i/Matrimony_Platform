<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX #2: Step5Request previously allowed 'extended' as a valid family_type,
 * but the DB enum only had ['nuclear', 'joint']. Submitting 'extended' would
 * cause a DB integrity error.
 *
 * Resolution: 'extended' has been removed from Step5Request validation rules
 * and from the ProfileSetupController view data, so only 'nuclear' and 'joint'
 * are presented and accepted.
 *
 * This migration is a no-op guard — it confirms the DB enum is correct.
 * To add 'extended' in the future, update this migration, Step5Request,
 * and the controller view data together.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Confirm/enforce DB enum matches application logic (nuclear, joint only)
        \DB::statement("ALTER TABLE user_profiles MODIFY COLUMN family_type ENUM('nuclear','joint') NULL");
    }

    public function down(): void
    {
        // No-op: restoring 'extended' here alone would re-introduce the mismatch
    }
};