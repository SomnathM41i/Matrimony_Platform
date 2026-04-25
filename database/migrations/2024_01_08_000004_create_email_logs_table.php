<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NEW TABLE: email_logs
// Reason: Prompt requires email configuration management and notification system.
// Every outgoing email must be logged for debugging, bounce tracking,
// re-send capability, and compliance.

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('to_email');
            $table->string('to_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();

            $table->string('subject');
            $table->string('mailable_class')->nullable();       // App\Mail\InterestReceived
            $table->string('template_key')->nullable();         // FK-like to notification_templates

            $table->enum('status', [
                'queued', 'sent', 'delivered', 'opened', 'clicked',
                'bounced', 'complained', 'failed'
            ])->default('queued');

            $table->string('message_id')->nullable();           // SMTP message-id
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable();               // ESP-specific data

            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['to_email', 'status']);
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
