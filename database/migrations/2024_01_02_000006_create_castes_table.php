<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("castes", function (Blueprint $table) {
            $table->id();
            $table->foreignId("religion_id")->constrained()->cascadeOnDelete();
            $table->string("name");
            $table->boolean("is_active")->default(true);
            $table->unsignedSmallInteger("sort_order")->default(0);
            $table->timestamps();
            $table->unique(["religion_id", "name"]);
            $table->index("religion_id");
        });
    }
    public function down(): void { Schema::dropIfExists("castes"); }
};
