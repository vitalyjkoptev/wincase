<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_network_sites', function (Blueprint $table) {
            $table->id();

            $table->string('domain', 200);
            $table->string('name', 200);
            $table->string('language', 5);
            $table->string('cms', 50)->nullable();
            $table->string('hosting', 100)->nullable();
            $table->unsignedSmallInteger('domain_authority')->default(0);
            $table->unsignedInteger('articles_total')->default(0);
            $table->unsignedInteger('articles_with_backlink')->default(0);
            $table->timestamp('last_article_at')->nullable();
            $table->string('status', 10)->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_network_sites');
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Миграция таблицы seo_network_sites — 8 сателлитных SEO-сайтов.
// Хранит: домен, CMS, хостинг, DA, количество статей, статус.
// Файл: database/migrations/2026_02_16_000005_create_seo_network_sites_table.php
// ---------------------------------------------------------------
