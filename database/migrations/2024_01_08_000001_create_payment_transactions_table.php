<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NEW TABLE: payment_transactions
// Reason: user_subscriptions stores payment info inline but there is no
// dedicated ledger for every gateway attempt, webhook, and refund.
// A production SaaS MUST have an immutable payment audit trail.

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_subscription_id')->nullable()
                  ->constrained()->nullOnDelete();

            // ── Transaction Identity ──────────────────────────────────
            $table->string('transaction_id')->unique();          // our internal ref
            $table->string('gateway_transaction_id')->nullable();// gateway's ref
            $table->string('gateway')->nullable();               // razorpay, stripe, etc.

            // ── Amount ───────────────────────────────────────────────
            $table->decimal('amount', 10, 2);
            $table->string('currency', 5)->default('INR');
            $table->decimal('gateway_fee', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);

            // ── Status ───────────────────────────────────────────────
            $table->enum('type', [
                'payment', 'refund', 'chargeback', 'adjustment'
            ])->default('payment');

            $table->enum('status', [
                'initiated', 'pending', 'completed', 'failed', 'refunded', 'disputed'
            ])->default('initiated');

            $table->string('failure_reason')->nullable();

            // ── Gateway Raw Response ──────────────────────────────────
            $table->json('gateway_request')->nullable();
            $table->json('gateway_response')->nullable();

            // ── Related Refund ────────────────────────────────────────
            $table->foreignId('refund_of_transaction_id')->nullable()
                  ->constrained('payment_transactions')->nullOnDelete();

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['gateway', 'gateway_transaction_id']);
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
