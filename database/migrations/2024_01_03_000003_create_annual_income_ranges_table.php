<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("annual_income_ranges", function (Blueprint $table) {
            $table->id();
            $table->string("label");
            $table->unsignedBigInteger("min_value")->default(0);
            $table->unsignedBigInteger("max_value")->nullable();
            $table->string("currency", 5)->default("INR");
            $table->boolean("is_active")->default(true);
            $table->unsignedSmallInteger("sort_order")->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists("annual_income_ranges"); }
};
