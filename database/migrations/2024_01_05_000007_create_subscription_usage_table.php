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
            $table->string('feature_key');           // e.g. 'interests_limit', 'messages_limit', 'contact_views'
            $table->unsignedInteger('used')->default(0); // cumulative total used during this subscription
            $table->timestamps();
            // One row per user+subscription+feature; no date partitioning needed
            $table->unique(['user_subscription_id', 'feature_key'], 'usage_sub_feature_unique');
            $table->index(['user_id', 'feature_key']);
        });
    }
    public function down(): void { Schema::dropIfExists('subscription_usage'); }
};