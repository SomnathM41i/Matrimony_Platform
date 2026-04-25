<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rm_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rm_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['call','email','whatsapp','meeting','note','profile_update']);
            $table->text('description');
            $table->timestamp('interacted_at')->useCurrent();
            $table->timestamps();
            $table->index(['rm_id', 'user_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('rm_interactions'); }
};
