<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("sub_castes", function (Blueprint $table) {
            $table->id();
            $table->foreignId("caste_id")->constrained()->cascadeOnDelete();
            $table->string("name");
            $table->boolean("is_active")->default(true);
            $table->unsignedSmallInteger("sort_order")->default(0);
            $table->timestamps();
            $table->index("caste_id");
        });
    }
    public function down(): void { Schema::dropIfExists("sub_castes"); }
};
