<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('relationship_manager_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rm_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('unassigned_at')->nullable();
            $table->timestamps();
            $table->index(['rm_id', 'is_active']);
            $table->index('user_id');
        });
    }
    public function down(): void { Schema::dropIfExists('relationship_manager_assignments'); }
};
