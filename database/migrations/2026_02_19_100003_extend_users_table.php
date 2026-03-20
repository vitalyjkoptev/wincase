<?php

// =====================================================
// FILE: database/migrations/2026_02_19_100003_extend_users_table.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role & Status
            $table->string('role', 20)->default('staff')->after('email');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('role');

            // Profile
            $table->string('phone', 30)->nullable()->after('status');
            $table->string('department', 100)->nullable()->after('phone');
            $table->string('avatar_url', 500)->nullable()->after('department');

            // 2FA
            $table->boolean('two_factor_enabled')->default(false)->after('avatar_url');
            $table->text('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');

            // Tracking
            $table->timestamp('last_login_at')->nullable()->after('two_factor_recovery_codes');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');

            // Indexes
            $table->index('role');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'status', 'phone', 'department', 'avatar_url',
                'two_factor_enabled', 'two_factor_secret', 'two_factor_recovery_codes',
                'last_login_at', 'last_login_ip',
            ]);
        });
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Миграция: расширение таблицы users.
// role (5 ролей), status (active/inactive), phone, department, avatar_url.
// 2FA: two_factor_enabled, two_factor_secret (encrypted), recovery_codes (encrypted).
// Tracking: last_login_at, last_login_ip.
// Файл: database/migrations/2026_02_19_100003_extend_users_table.php
// ---------------------------------------------------------------
