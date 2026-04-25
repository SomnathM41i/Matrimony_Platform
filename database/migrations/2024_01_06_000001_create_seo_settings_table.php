<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_identifier')->unique();
            $table->string('page_type')->default('static');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->enum('robots', ['index,follow','noindex,nofollow','index,nofollow','noindex,follow'])
                  ->default('index,follow');
            $table->string('profile_title_template')->nullable();
            $table->text('profile_description_template')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('seo_settings'); }
};
