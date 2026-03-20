<?php

// =====================================================
// FILE: database/migrations/2026_02_16_000010_update_social_tables_add_threads_linkedin.php
// =====================================================
// Combined ALTER migration for all social_* tables
// Adding Threads and LinkedIn platform support

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // --- 1. social_accounts: add threads_id column ---
        Schema::table('social_accounts', function (Blueprint $table) {
            $table->string('threads_user_id', 100)->nullable()->after('platform');
            $table->string('linkedin_company_id', 100)->nullable()->after('threads_user_id');
        });

        // --- 2. social_posts: add threads_id + linkedin_post_id ---
        Schema::table('social_posts', function (Blueprint $table) {
            $table->string('threads_id', 100)->nullable()->after('platform');
            $table->string('linkedin_post_id', 100)->nullable()->after('threads_id');
        });

        // --- 3. social_analytics: no schema change needed ---
        // Platform values for threads/linkedin are handled by SocialPlatformEnum
        // which is stored as string in the database.

        // --- 4. content_calendar: add platforms JSON column ---
        if (Schema::hasTable('content_calendar')) {
            Schema::table('content_calendar', function (Blueprint $table) {
                $table->json('platforms')->nullable()->after('content');
            });
        }
    }

    public function down(): void
    {
        Schema::table('social_accounts', function (Blueprint $table) {
            $table->dropColumn(['threads_user_id', 'linkedin_company_id']);
        });

        Schema::table('social_posts', function (Blueprint $table) {
            $table->dropColumn(['threads_id', 'linkedin_post_id']);
        });

        if (Schema::hasTable('content_calendar')) {
            Schema::table('content_calendar', function (Blueprint $table) {
                $table->dropColumn('platforms');
            });
        }
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Комбинированная миграция обновлений существующих таблиц для поддержки
// Threads и LinkedIn. Все ALTER в одном файле для атомарности.
// 1. social_accounts — добавлены threads_user_id, linkedin_company_id
// 2. social_posts — добавлены threads_id, linkedin_post_id
// 3. content_calendar — добавлен platforms JSON (массив платформ)
// Laravel 12 с PHP 8.4 backed enums — значения платформ управляются
// через SocialPlatformEnum, не нужны ALTER ENUM в MySQL.
// Файл: database/migrations/2026_02_16_000010_update_social_tables_add_threads_linkedin.php
// ---------------------------------------------------------------
