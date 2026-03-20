<?php

// =====================================================
// FILE: database/migrations/2026_02_19_100004_create_news_tables.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =====================================================
        // news_articles — parsed + rewritten + published articles
        // =====================================================
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();

            // Source
            $table->string('source_key', 50)->index();
            $table->string('source_name', 200);
            $table->string('source_url', 2000)->unique();

            // Original content
            $table->string('original_title', 500);
            $table->longText('original_content')->nullable();
            $table->text('original_description')->nullable();
            $table->char('original_language', 2)->default('pl');

            // Category & Priority
            $table->string('category', 50)->index();
            $table->enum('priority', ['critical', 'high', 'medium', 'low'])->default('medium');
            $table->string('image_url', 2000)->nullable();
            $table->timestamp('published_at')->nullable(); // original pub date

            // Rewritten content (AI)
            $table->string('rewritten_title', 500)->nullable();
            $table->longText('rewritten_content')->nullable();
            $table->text('rewritten_description')->nullable();
            $table->char('rewritten_language', 2)->nullable();
            $table->timestamp('rewritten_at')->nullable();

            // SEO
            $table->string('seo_meta_title', 70)->nullable();
            $table->string('seo_meta_description', 160)->nullable();
            $table->string('seo_keywords', 300)->nullable();
            $table->string('seo_slug', 300)->nullable();

            // Quality
            $table->float('plagiarism_score')->nullable(); // 0-100, lower=better
            $table->string('skip_reason', 200)->nullable();

            // Publishing
            $table->enum('status', [
                'parsed', 'rewritten', 'needs_review', 'approved',
                'translating', 'published', 'rejected', 'skipped', 'rewrite_failed',
            ])->default('parsed')->index();

            $table->json('published_to')->nullable(); // ['polandpulse', 'wincase_blog']
            $table->json('published_urls')->nullable();
            $table->timestamp('last_published_at')->nullable();

            // Parent (for translations)
            $table->foreignId('parent_article_id')->nullable()
                  ->constrained('news_articles')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Performance indexes
            $table->index(['status', 'priority']);
            $table->index(['category', 'created_at']);
            $table->index('parent_article_id');
        });

        // =====================================================
        // news_publish_logs — publication history
        // =====================================================
        Schema::create('news_publish_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_article_id')->constrained('news_articles')->cascadeOnDelete();
            $table->string('target_site', 50);
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->text('error_message')->nullable();
            $table->string('published_url', 2000)->nullable();
            $table->timestamps();

            $table->index(['target_site', 'status']);
        });

        // =====================================================
        // news_feed_logs — live feed event history
        // =====================================================
        Schema::create('news_feed_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 30)->index(); // parsed, rewritten, translated, published, error
            $table->unsignedBigInteger('article_id')->nullable();
            $table->string('source', 200)->nullable();
            $table->string('target_site', 100)->nullable();
            $table->string('title', 500)->nullable();
            $table->char('language', 2)->nullable();
            $table->json('message')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_feed_logs');
        Schema::dropIfExists('news_publish_logs');
        Schema::dropIfExists('news_articles');
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// 3 таблицы: news_articles (30+ колонок), news_publish_logs, news_feed_logs.
// news_articles: source → original → rewritten (AI) → SEO → published.
// 9 статусов: parsed → rewritten → published (+ needs_review, translating, rejected, skipped).
// parent_article_id — связь оригинал → переводы.
// published_to JSON — массив сайтов куда опубликовано.
// plagiarism_score — float 0-100 (ниже = лучше).
// Файл: database/migrations/2026_02_19_100004_create_news_tables.php
// ---------------------------------------------------------------
