<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('partner_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('age_min')->nullable();
            $table->unsignedTinyInteger('age_max')->nullable();
            $table->unsignedSmallInteger('height_min_cm')->nullable();
            $table->unsignedSmallInteger('height_max_cm')->nullable();
            $table->json('marital_status')->nullable();

            $table->json('religion_ids')->nullable();
            $table->json('caste_ids')->nullable();
            $table->json('sub_caste_ids')->nullable();
            $table->json('mother_tongue_ids')->nullable();
            $table->boolean('caste_no_bar')->default(false);

            $table->json('country_ids')->nullable();
            $table->json('state_ids')->nullable();
            $table->json('city_ids')->nullable();
            $table->string('residency_status_pref')->nullable();

            $table->json('education_level_ids')->nullable();
            $table->json('profession_ids')->nullable();
            $table->foreignId('annual_income_range_id_min')->nullable()
                  ->references('id')->on('annual_income_ranges')->nullOnDelete();

            $table->json('diet')->nullable();
            $table->json('smoking')->nullable();
            $table->json('drinking')->nullable();

            $table->json('rashi_ids')->nullable();
            $table->enum('manglik_pref', ['yes','no','partial','any'])->default('any');

            $table->text('about_partner')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('partner_preferences'); }
};
