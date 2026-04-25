<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NEW TABLE: robots_txt_settings
// Reason: Prompt explicitly requires Robots.txt management from Admin Panel.
// seo_settings has per-page robots meta, but global robots.txt rules
// (Allow/Disallow/Crawl-delay/Sitemap directives) need their own table.

return new class extends Migration {
    public function up(): void
    {
        Schema::create('robots_txt_settings', function (Blueprint $table) {
            $table->id();

            $table->string('user_agent')->default('*');         // e.g. * / Googlebot
            $table->enum('directive', [
                'allow', 'disallow', 'crawl_delay', 'sitemap', 'host'
            ]);
            $table->string('value');                            // path, URL, seconds, etc.
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->index(['user_agent', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('robots_txt_settings');
    }
};
