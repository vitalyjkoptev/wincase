<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_listings', function (Blueprint $table) {
            $table->id();

            $table->string('platform', 200);
            $table->string('url', 500)->nullable();
            $table->string('domain', 100)->nullable();
            $table->string('nap_name', 200)->nullable();
            $table->string('nap_address', 500)->nullable();
            $table->string('nap_phone', 30)->nullable();
            $table->boolean('nap_consistent')->default(false);
            $table->string('status', 15)->default('pending');
            $table->timestamp('last_checked_at')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('nap_consistent');
            $table->index('domain');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_listings');
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Миграция таблицы brand_listings — 50+ каталогов бизнес-листингов.
// NAP check (Name-Address-Phone) для всех 4 доменов WinCase.
// nap_consistent — флаг совпадения с эталонными данными.
// Файл: database/migrations/2026_02_16_000006_create_brand_listings_table.php
// ---------------------------------------------------------------
