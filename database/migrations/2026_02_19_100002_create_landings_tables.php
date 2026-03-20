<?php

// =====================================================
// FILE: database/migrations/2026_02_19_100002_create_landings_tables.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =====================================================
        // Landing Pages
        // =====================================================

        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->string('domain');                           // wincase.pro, wincase-legalization.com, etc.
            $table->string('slug', 200);                        // /karta-pobytu, /work-permit, etc.
            $table->string('language', 5)->default('pl');
            $table->string('title', 200);
            $table->string('meta_description', 300)->nullable();
            $table->string('service_type', 50)->nullable();     // legalization, work_permit, etc.
            $table->enum('status', ['draft', 'active', 'paused', 'archived'])->default('draft');
            $table->string('ad_platform', 30)->nullable();      // google_ads, facebook_ads, etc.
            $table->string('target_audience', 100)->nullable();
            $table->timestamps();

            $table->unique(['domain', 'slug', 'language']);
            $table->index('status');
        });

        // =====================================================
        // A/B Test Variants
        // =====================================================

        Schema::create('landing_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_id')->constrained()->cascadeOnDelete();
            $table->string('variant_name', 50);                 // 'A', 'B', 'C' or descriptive
            $table->string('headline', 200)->nullable();
            $table->string('cta_text', 100)->nullable();
            $table->string('cta_color', 20)->nullable();        // #hex
            $table->text('custom_css')->nullable();
            $table->unsignedTinyInteger('traffic_pct')->default(50);  // 0-100
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('visits_count')->default(0);
            $table->unsignedInteger('conversions_count')->default(0);
            $table->timestamps();

            $table->index(['landing_page_id', 'is_active']);
        });

        // =====================================================
        // Visit Tracking
        // =====================================================

        Schema::create('landing_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_variant_id')->constrained()->cascadeOnDelete();
            $table->string('visitor_ip', 45)->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 200)->nullable();
            $table->string('utm_content', 200)->nullable();
            $table->string('referer', 500)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('language', 5)->default('pl');
            $table->enum('device_type', ['desktop', 'mobile', 'tablet'])->default('desktop');
            $table->timestamp('created_at')->useCurrent();

            $table->index('utm_source');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_visits');
        Schema::dropIfExists('landing_variants');
        Schema::dropIfExists('landing_pages');
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Миграция: 3 таблицы для Landings модуля.
// landing_pages: domain + slug + language (unique), status, service_type, ad_platform.
// landing_variants: A/B варианты с traffic_pct, headline, CTA, visits/conversions counters.
// landing_visits: tracking с UTM, device_type, referer, language.
// Файл: database/migrations/2026_02_19_100002_create_landings_tables.php
// ---------------------------------------------------------------
