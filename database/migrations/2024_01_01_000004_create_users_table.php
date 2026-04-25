<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("users", function (Blueprint $table) {
            $table->id();

            // 🔗 Relationships
            $table->foreignId("role_id")->constrained()->restrictOnDelete();
            $table->foreignId("assigned_rm_id")->nullable()
                  ->references("id")->on("users")->nullOnDelete();

            // 👤 Basic Info
            $table->string("name");
            $table->string("email")->unique();
            $table->string("phone", 20)->nullable()->index();
            $table->string("password");

            // 📧 Laravel default
            $table->timestamp("email_verified_at")->nullable();
            $table->rememberToken();

            // 🧑 Profile Info
            $table->enum("gender", ["male","female","other"])->nullable();
            $table->date("date_of_birth")->nullable();
            $table->string("profile_photo")->nullable();
            $table->string("profile_slug")->unique()->nullable();

            // 💎 Account & Profile Status
            $table->enum("account_status", ["active","inactive","suspended","pending"])->default("pending");
            $table->enum("profile_status", ["incomplete","complete","verified"])->default("incomplete");

            // 💰 Premium
            $table->boolean("is_premium")->default(false);
            $table->timestamp("premium_expires_at")->nullable();

            // 🔐 Verification
            $table->string("email_verification_token")->nullable();
            $table->string("phone_verification_otp", 10)->nullable();
            $table->timestamp("phone_verified_at")->nullable();

            // 🛡️ Security / Tracking
            $table->string("last_login_ip", 45)->nullable();
            $table->timestamp("last_login_at")->nullable();

            // 🧹 Soft Delete + timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes
            $table->index(["gender", "account_status"]);
            $table->index(["is_premium", "account_status"]);
        });

        // 🔑 Password Reset Table (keep separate)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 🔐 Sessions Table (keep separate)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("users");
        Schema::dropIfExists("password_reset_tokens");
        Schema::dropIfExists("sessions");
    }
};