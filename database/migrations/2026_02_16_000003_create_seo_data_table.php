<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_data', function (Blueprint $table) {
            $table->id();

            $table->string('domain', 100);
            $table->date('date');
            $table->string('source', 10);

            // --- GSC Metrics ---
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('impressions')->default(0);
            $table->decimal('avg_position', 6, 2)->nullable();

            // --- GA4 Metrics ---
            $table->unsignedInteger('users')->default(0);
            $table->unsignedInteger('sessions')->default(0);
            $table->unsignedInteger('conversions')->default(0);

            // --- SEO Metrics ---
            $table->unsignedSmallInteger('domain_authority')->nullable();
            $table->unsignedInteger('backlinks')->default(0);
            $table->unsignedInteger('referring_domains')->default(0);

            $table->json('raw_data')->nullable();
            $table->timestamps();

            // --- Unique & Indexes ---
            $table->unique(['domain', 'date', 'source'], 'unique_domain_date_source');
            $table->index('domain');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_data');
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Миграция таблицы seo_data — SEO данные для 4 доменов + SEO-сайтов.
// Источники: gsc, ga4, ahrefs, moz. UNIQUE: домен + дата + источник.
// Содержит метрики GSC (clicks, impressions, avg_position),
// GA4 (users, sessions, conversions), SEO (DA, backlinks, referring_domains).
// Файл: database/migrations/2026_02_16_000003_create_seo_data_table.php
// ---------------------------------------------------------------
