<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NEW TABLE: whatsapp_logs
// Reason: Prompt requires WhatsApp support as a feature-toggled notification channel.
// notification_templates has whatsapp_enabled flag but no delivery log exists.

return new class extends Migration {
    public function up(): void
    {
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('to_phone', 20);
            $table->string('template_name')->nullable();        // WhatsApp approved template
            $table->text('message_body')->nullable();
            $table->json('template_params')->nullable();        // dynamic variables

            $table->string('provider')->nullable();             // twilio / wati / messagebird
            $table->string('provider_message_id')->nullable();  // provider's message ID

            $table->enum('status', [
                'queued', 'sent', 'delivered', 'read', 'failed'
            ])->default('queued');

            $table->string('error_code')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('to_phone');
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
