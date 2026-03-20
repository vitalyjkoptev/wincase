<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            // --- Contact Info ---
            $table->string('name', 100);
            $table->string('phone', 30);
            $table->string('email', 100)->nullable();

            // --- Service & Message ---
            $table->string('service_type', 30)->default('other');
            $table->text('message')->nullable();

            // --- Source Tracking ---
            $table->string('source', 20);
            $table->string('utm_source', 200)->nullable();
            $table->string('utm_medium', 200)->nullable();
            $table->string('utm_campaign', 200)->nullable();
            $table->string('utm_term', 200)->nullable();
            $table->string('utm_content', 200)->nullable();
            $table->string('gclid', 200)->nullable();
            $table->string('fbclid', 200)->nullable();
            $table->string('ttclid', 200)->nullable();
            $table->string('landing_page', 500)->nullable();

            // --- Visitor Info ---
            $table->string('language', 5)->default('en');
            $table->string('device', 10)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('city', 100)->nullable();

            // --- CRM Status ---
            $table->string('status', 20)->default('new');
            $table->foreignId('assigned_to')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->string('priority', 10)->default('medium');
            $table->text('notes')->nullable();

            // --- Conversion Timestamps ---
            $table->timestamp('first_contact_at')->nullable();
            $table->timestamp('consultation_at')->nullable();
            $table->timestamp('converted_at')->nullable();

            // --- Link to CRM ---
            $table->foreignId('client_id')->nullable()
                  ->constrained('clients')->nullOnDelete();
            $table->foreignId('case_id')->nullable()
                  ->constrained('cases')->nullOnDelete();

            // --- GDPR ---
            $table->boolean('gdpr_consent')->default(false);
            $table->timestamp('gdpr_consent_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // --- Indexes ---
            $table->index('status');
            $table->index('source');
            $table->index('assigned_to');
            $table->index('language');
            $table->index('created_at');
            $table->index('gclid');
            $table->index('fbclid');
            $table->index('ttclid');
            $table->index('client_id');
            $table->index('priority');
            $table->index(['status', 'created_at']);
            $table->index(['source', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Миграция таблицы leads — 31 колонка. Хранит все лиды из 14 источников.
// Включает: контакты, тип услуги, источник+UTM+clickIDs,
// данные посетителя (язык/устройство/IP/гео), воронку статусов,
// связь с CRM (client_id, case_id), GDPR consent, soft deletes.
// 12 индексов для быстрого поиска и фильтрации.
// Laravel 12 использует string вместо enum для совместимости с PHP 8.4 backed enums.
// Файл: database/migrations/2026_02_16_000001_create_leads_table.php
// ---------------------------------------------------------------
