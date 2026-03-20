<?php

// =====================================================
// FILE: database/migrations/2026_02_19_100001_create_brand_listings_table.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('brand_listings')) return;
        Schema::create('brand_listings', function (Blueprint $table) {
            $table->id();
            $table->string('directory_name');
            $table->string('directory_group', 50)->index();
            $table->string('listing_url', 500)->nullable();
            $table->enum('status', ['not_submitted', 'pending', 'claimed', 'verified', 'suspended'])
                  ->default('not_submitted');
            $table->string('listed_name')->nullable();
            $table->string('listed_address', 500)->nullable();
            $table->string('listed_phone', 30)->nullable();
            $table->string('listed_email')->nullable();
            $table->string('listed_website', 500)->nullable();
            $table->boolean('nap_consistent')->nullable();
            $table->json('nap_issues')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique('directory_name');
        });

        // =====================================================
        // Reviews table
        // =====================================================

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 30)->index();          // google, trustpilot, facebook, gowork
            $table->string('platform_review_id')->index();     // unique ID from platform
            $table->string('author_name');
            $table->tinyInteger('rating')->unsigned();         // 1-5
            $table->text('text')->nullable();
            $table->string('language', 5)->default('pl');
            $table->timestamp('published_at')->nullable();
            $table->text('reply_text')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->boolean('needs_response')->default(false);
            $table->boolean('is_featured')->default(false);    // show on website
            $table->timestamps();

            $table->unique(['platform', 'platform_review_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('brand_listings');
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Миграция: brand_listings (50+ каталогов) и reviews (отзывы).
// brand_listings: directory_name (unique), status, NAP поля, nap_consistent, nap_issues (JSON).
// reviews: platform + platform_review_id (unique), rating 1-5, reply_text, needs_response.
// Файл: database/migrations/2026_02_19_100001_create_brand_listings_table.php
// ---------------------------------------------------------------
