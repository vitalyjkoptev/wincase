<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->string('platform', 20);
            $table->string('author_name', 200)->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('text')->nullable();
            $table->text('reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('language', 5)->nullable();
            $table->string('platform_review_id', 200)->nullable();

            $table->timestamps();

            $table->index('platform');
            $table->index('rating');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Миграция таблицы reviews — отзывы со всех платформ
// (Google, Trustpilot, Facebook, GoWork, Clutch, ProvenExpert).
// Хранит: автор, рейтинг, текст, ответ компании, дата публикации, язык.
// Файл: database/migrations/2026_02_16_000004_create_reviews_table.php
// ---------------------------------------------------------------
