<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // ── Identity ─────────────────────────────────────────────────────
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['user', 'admin', 'superAdmin'])->default('user')->index();

            // ── Profile ──────────────────────────────────────────────────────
            $table->string('phone', 30)->nullable();
            $table->string('job_title', 100)->nullable();
            $table->string('organization', 150)->nullable();
            $table->string('timezone', 50)->nullable()->default('UTC');
            $table->string('locale', 10)->nullable()->default('en');
            $table->string('avatar_url')->nullable();

            // ── Two-Factor Authentication ─────────────────────────────────────
            $table->string('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();

            // ── Security & Audit ─────────────────────────────────────────────
            $table->string('last_login_ip', 45)->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->unsignedSmallInteger('failed_login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable()
                  ->comment('Account lockout expiry after brute-force threshold');

            // ── Preferences ──────────────────────────────────────────────────
            $table->json('preferences')->nullable()
                  ->comment('User-specific UI and notification preferences');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // ── Composite Indexes ────────────────────────────────────────────
            $table->index(['role', 'deleted_at']);
            $table->index('last_login_at');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 191)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable()->index();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
