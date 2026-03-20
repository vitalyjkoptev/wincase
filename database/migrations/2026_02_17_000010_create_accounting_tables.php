<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === 1. ACCOUNTING PERIODS (monthly/quarterly/annual) ===
        Schema::create('accounting_periods', function (Blueprint $table) {
            $table->id();

            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->string('period_type', 10)->default('monthly');
            $table->string('tax_regime', 20);

            // --- Revenue & Costs ---
            $table->decimal('gross_revenue', 12, 2)->default(0.00);
            $table->decimal('net_revenue', 12, 2)->default(0.00);
            $table->decimal('total_costs', 12, 2)->default(0.00);
            $table->decimal('taxable_income', 12, 2)->default(0.00);

            // --- VAT ---
            $table->decimal('vat_output', 10, 2)->default(0.00);
            $table->decimal('vat_input', 10, 2)->default(0.00);
            $table->decimal('vat_due', 10, 2)->default(0.00);

            // --- PIT / CIT ---
            $table->decimal('income_tax_advance', 10, 2)->default(0.00);
            $table->decimal('income_tax_cumulative', 12, 2)->default(0.00);

            // --- ZUS ---
            $table->decimal('zus_social', 10, 2)->default(0.00);
            $table->decimal('zus_health', 10, 2)->default(0.00);
            $table->decimal('zus_total', 10, 2)->default(0.00);

            // --- Totals ---
            $table->decimal('total_tax_burden', 12, 2)->default(0.00);
            $table->decimal('net_income_after_tax', 12, 2)->default(0.00);

            $table->string('status', 15)->default('open');
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()
                  ->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->unique(['year', 'month', 'tax_regime'], 'unique_period');
            $table->index(['year', 'status']);
        });

        // === 2. TAX REPORTS (generated documents for tax office) ===
        Schema::create('tax_reports', function (Blueprint $table) {
            $table->id();

            $table->string('report_type', 30);
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month')->nullable();
            $table->unsignedTinyInteger('quarter')->nullable();
            $table->string('tax_regime', 20);

            // --- Report Data ---
            $table->json('report_data');
            $table->json('line_items')->nullable();

            // --- Totals ---
            $table->decimal('total_revenue', 12, 2)->default(0.00);
            $table->decimal('total_costs', 12, 2)->default(0.00);
            $table->decimal('total_tax', 12, 2)->default(0.00);
            $table->decimal('total_vat', 10, 2)->default(0.00);
            $table->decimal('total_zus', 10, 2)->default(0.00);

            // --- Status ---
            $table->string('status', 15)->default('draft');
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('generated_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();

            // --- File ---
            $table->string('file_path', 500)->nullable();

            $table->timestamps();

            $table->index('report_type');
            $table->index(['year', 'month']);
            $table->index('status');
        });

        // === 3. EXPENSE RECORDS (for cost deduction if not ryczalt) ===
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->string('category', 50);
            $table->string('description', 500);
            $table->string('vendor', 200)->nullable();
            $table->string('vendor_nip', 15)->nullable();
            $table->string('invoice_number', 100)->nullable();

            $table->decimal('net_amount', 10, 2);
            $table->string('vat_rate', 5)->default('23');
            $table->decimal('vat_amount', 10, 2)->default(0.00);
            $table->decimal('gross_amount', 10, 2);

            $table->string('payment_method', 20)->nullable();
            $table->boolean('is_tax_deductible')->default(true);
            $table->decimal('deductible_percentage', 5, 2)->default(100.00);
            $table->string('file_path', 500)->nullable();

            $table->timestamps();

            $table->index('date');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('tax_reports');
        Schema::dropIfExists('accounting_periods');
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Миграция бухгалтерского модуля — 3 таблицы:
//
// 1. accounting_periods — месячные периоды учёта.
//    Хранит: выручка/расходы/налогооблагаемый доход, VAT (output/input/due),
//    PIT/CIT аванс, ZUS (социальные/здоровье/итого), общая нагрузка,
//    чистый доход после всех налогов. Закрывается владельцем в конце месяца.
//
// 2. tax_reports — сгенерированные отчёты для налоговой.
//    Типы: jpk_vat, pit_advance, pit_annual, cit_advance, cit_annual,
//    zus_dra, ryczalt_monthly. JSON report_data + line_items.
//    Статусы: draft → generated → submitted (отправка вручную).
//
// 3. expenses — записи расходов (для вычета при skala/liniowy/CIT).
//    Каждый расход: дата, категория, описание, NIP поставщика, сумма,
//    ставка VAT, является ли вычитаемым, файл скана чека.
//
// Файл: database/migrations/2026_02_17_000010_create_accounting_tables.php
// ---------------------------------------------------------------
