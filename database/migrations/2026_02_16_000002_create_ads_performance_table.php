<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads_performance', function (Blueprint $table) {
            $table->id();

            $table->string('platform', 20);
            $table->string('campaign_id', 100);
            $table->string('campaign_name', 200);
            $table->date('date');

            // --- Metrics ---
            $table->unsignedInteger('impressions')->default(0);
            $table->unsignedInteger('clicks')->default(0);
            $table->decimal('cost', 10, 2)->default(0.00);
            $table->unsignedInteger('conversions')->default(0);
            $table->decimal('conversion_value', 10, 2)->default(0.00);

            // --- Calculated ---
            $table->decimal('cpc', 8, 4)->default(0.0000);
            $table->decimal('cpl', 8, 4)->default(0.0000);
            $table->decimal('ctr', 5, 4)->default(0.0000);
            $table->unsignedInteger('leads_count')->default(0);

            $table->string('status', 15)->default('active');
            $table->json('raw_data')->nullable();

            $table->timestamps();

            // --- Unique & Indexes ---
            $table->unique(['platform', 'campaign_id', 'date'], 'unique_campaign_date');
            $table->index('platform');
            $table->index('date');
            $table->index(['platform', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads_performance');
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Миграция таблицы ads_performance — ежедневные метрики рекламных кампаний.
// 5 платформ (Google/Meta/TikTok/Pinterest/YouTube Ads).
// UNIQUE constraint: одна запись на кампанию в день.
// raw_data JSON — полный ответ API для детального анализа.
// Файл: database/migrations/2026_02_16_000002_create_ads_performance_table.php
// ---------------------------------------------------------------
