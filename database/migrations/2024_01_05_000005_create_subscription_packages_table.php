<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// FIX: Added 'trial_days' column for free trial support — required for
// production SaaS subscription management from Admin Panel.
// CHANGE: Limits are now totals for the full subscription duration, not per-day.
//   - interests_per_day  → interests_limit   (total interests allowed during plan)
//   - messages_per_day   → messages_limit    (total messages allowed during plan)
//   - contact_views      → contact_views     (total contact views allowed, 0 = unlimited)
//   - photo_gallery_limit stays the same     (max photos user can upload)

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 5)->default('INR');
            $table->unsignedSmallInteger('duration_days');
            $table->unsignedSmallInteger('trial_days')->default(0);   // FIX: added

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);

            // ── Feature Limits (totals for the full plan duration) ────
            $table->integer('contact_views')->default(0);              // 0 = unlimited
            $table->integer('interests_limit')->default(0);            // 0 = unlimited
            $table->integer('messages_limit')->default(0);             // 0 = unlimited
            $table->integer('photo_gallery_limit')->default(3);

            // ── Feature Flags (per package) ───────────────────────────
            $table->boolean('can_see_contact')->default(false);
            $table->boolean('can_see_full_horoscope')->default(false);
            $table->boolean('highlight_profile')->default(false);
            $table->boolean('priority_in_search')->default(false);
            $table->boolean('whatsapp_support')->default(false);       // added
            $table->boolean('rm_assistance')->default(false);          // added

            $table->json('extra_features')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_packages');
    }
};