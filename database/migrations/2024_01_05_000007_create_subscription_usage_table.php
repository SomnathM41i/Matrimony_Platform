<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscription_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_subscription_id')->constrained()->cascadeOnDelete();
            $table->string('feature_key');
            $table->unsignedInteger('used')->default(0);
            $table->date('usage_date')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'feature_key', 'usage_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('subscription_usage'); }
};
