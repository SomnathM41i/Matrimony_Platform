<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_analytics_snapshots', function (Blueprint $table) {
            $table->id();
            $table->date('snapshot_date')->unique();
            $table->unsignedInteger('total_users')->default(0);
            $table->unsignedInteger('new_registrations')->default(0);
            $table->unsignedInteger('active_subscriptions')->default(0);
            $table->unsignedInteger('interests_sent')->default(0);
            $table->unsignedInteger('interests_accepted')->default(0);
            $table->unsignedInteger('messages_sent')->default(0);
            $table->decimal('revenue', 12, 2)->default(0);
            $table->json('extra_data')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('admin_analytics_snapshots'); }
};
