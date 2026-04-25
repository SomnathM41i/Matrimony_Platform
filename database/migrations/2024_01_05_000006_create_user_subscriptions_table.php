<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_package_id')->constrained()->restrictOnDelete();
            $table->decimal('amount_paid', 10, 2);
            $table->string('currency', 5)->default('INR');
            $table->enum('payment_status', ['pending','completed','failed','refunded'])->default('pending');
            $table->string('payment_gateway')->nullable();
            $table->string('transaction_id')->nullable()->unique();
            $table->json('gateway_response')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->index(['user_id', 'is_active']);
        });
    }
    public function down(): void { Schema::dropIfExists('user_subscriptions'); }
};
