<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landings', function (Blueprint $table) {
            $table->id();

            $table->string('domain', 100);
            $table->string('path', 300);
            $table->string('language', 5);
            $table->string('title', 200);
            $table->string('audience', 200)->nullable();
            $table->json('traffic_sources')->nullable();
            $table->string('ab_variant', 5)->nullable();
            $table->boolean('is_active')->default(true);

            // --- Metrics ---
            $table->unsignedInteger('views_total')->default(0);
            $table->unsignedInteger('submissions_total')->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0.00);
            $table->unsignedSmallInteger('pagespeed_score')->nullable();

            $table->timestamps();

            $table->unique(['domain', 'path'], 'unique_landing');
            $table->index('language');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landings');
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Миграция таблицы landings — 14+ лендинговых страниц на 4 доменах.
// Хранит: домен, путь, язык, аудиторию, A/B вариант, метрики
// (просмотры, заявки, conversion rate, PageSpeed score).
// UNIQUE: домен + путь.
// Файл: database/migrations/2026_02_16_000007_create_landings_table.php
// ---------------------------------------------------------------
