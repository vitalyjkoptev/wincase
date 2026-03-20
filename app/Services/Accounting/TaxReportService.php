<?php

namespace App\Services\Accounting;

use App\Enums\TaxRegimeEnum;
use App\Enums\VatRateEnum;
use App\Models\AccountingPeriod;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\PosTransaction;
use App\Models\TaxReport;
use Illuminate\Support\Facades\Log;

class TaxReportService
{
    public function __construct(
        protected TaxCalculatorService $calculator
    ) {}

    // =====================================================
    // 1. MONTHLY TAX REPORT (Full — for accountant)
    // =====================================================

    /**
     * Generate complete monthly tax report with all calculations.
     * This is the main report that includes PIT/CIT + VAT + ZUS.
     */
    public function generateMonthlyTaxReport(
        int $year,
        int $month,
        TaxRegimeEnum $regime,
        int $userId,
        float $ryczaltRate = 15.0,
        string $zusStage = 'full'
    ): TaxReport {
        $config = config('polish_tax');

        // --- Gather revenue data from POS + invoices ---
        $revenue = $this->getMonthlyRevenue($year, $month);
        $expenses = Expense::monthlySummary($year, $month);
        $cumulative = AccountingPeriod::cumulativeForYear($year, $month - 1);

        // --- Calculate all taxes ---
        $taxCalc = $this->calculator->calculateMonthlyTotal(
            regime: $regime,
            monthlyGrossRevenue: $revenue['total_gross'],
            monthlyCosts: $regime->allowsCostDeduction() ? $expenses['deductible_net'] : 0,
            vatOutput: $revenue['total_vat_collected'],
            vatInput: $expenses['deductible_vat'],
            cumulativeIncome: $cumulative['taxable_income'] ?? 0,
            previousPitAdvances: $cumulative['income_tax_advances'] ?? 0,
            zusStage: $zusStage,
            ryczaltRate: $ryczaltRate
        );

        // --- Build report data ---
        $reportData = [
            'company' => $config['company'],
            'period' => ['year' => $year, 'month' => $month],
            'regime' => $regime->value,
            'regime_label' => $regime->label(),
            'pit_form' => $regime->pitForm(),

            'revenue' => [
                'invoices_count' => $revenue['invoices_count'],
                'pos_transactions_count' => $revenue['pos_count'],
                'total_gross' => $revenue['total_gross'],
                'total_net' => $revenue['total_net'],
                'total_vat_collected' => $revenue['total_vat_collected'],
                'by_service_type' => $revenue['by_service'],
                'by_payment_method' => $revenue['by_method'],
                'by_vat_rate' => $revenue['by_vat_rate'],
            ],

            'costs' => $regime->allowsCostDeduction() ? [
                'total_net' => $expenses['total_net'],
                'total_vat_input' => $expenses['total_vat'],
                'total_gross' => $expenses['total_gross'],
                'deductible_net' => $expenses['deductible_net'],
                'deductible_vat' => $expenses['deductible_vat'],
                'by_category' => $expenses['by_category'],
            ] : ['note' => 'Cost deduction not available for ryczałt regime'],

            'tax_calculation' => $taxCalc,

            'cumulative_year_to_date' => [
                'total_revenue' => ($cumulative['gross_revenue'] ?? 0) + $revenue['total_gross'],
                'total_costs' => ($cumulative['total_costs'] ?? 0) + ($expenses['deductible_net'] ?? 0),
                'total_pit_advances' => ($cumulative['income_tax_advances'] ?? 0) + ($taxCalc['totals']['income_tax_due'] ?? 0),
                'total_vat_paid' => ($cumulative['vat_due'] ?? 0) + $taxCalc['vat']['vat_due'],
                'total_zus_paid' => ($cumulative['zus_total'] ?? 0) + $taxCalc['zus']['total_zus'],
            ],

            'payment_deadlines' => [
                'pit_advance' => sprintf('%04d-%02d-%02d', $year, $month + 1 > 12 ? 1 : $month + 1, $config['deadlines']['pit_advance_monthly']),
                'vat_jpk' => sprintf('%04d-%02d-%02d', $year, $month + 1 > 12 ? 1 : $month + 1, $config['deadlines']['jpk_vat_monthly']),
                'zus_dra' => sprintf('%04d-%02d-%02d', $year, $month + 1 > 12 ? 1 : $month + 1, $config['deadlines']['zus_jdg']),
            ],
        ];

        // --- Build line items (individual transactions) ---
        $lineItems = $this->getMonthlyLineItems($year, $month);

        // --- Save report ---
        $report = TaxReport::create([
            'report_type' => TaxReport::TYPE_TAX_SUMMARY,
            'year' => $year,
            'month' => $month,
            'tax_regime' => $regime,
            'report_data' => $reportData,
            'line_items' => $lineItems,
            'total_revenue' => $revenue['total_gross'],
            'total_costs' => $expenses['deductible_net'] ?? 0,
            'total_tax' => $taxCalc['totals']['income_tax_due'] ?? 0,
            'total_vat' => $taxCalc['vat']['vat_due'],
            'total_zus' => $taxCalc['zus']['total_zus'],
            'status' => 'generated',
            'generated_at' => now(),
            'generated_by' => $userId,
        ]);

        // --- Update or create accounting period ---
        AccountingPeriod::updateOrCreate(
            ['year' => $year, 'month' => $month, 'tax_regime' => $regime],
            [
                'gross_revenue' => $revenue['total_gross'],
                'net_revenue' => $revenue['total_net'],
                'total_costs' => $expenses['deductible_net'] ?? 0,
                'taxable_income' => $revenue['total_net'] - ($expenses['deductible_net'] ?? 0),
                'vat_output' => $revenue['total_vat_collected'],
                'vat_input' => $expenses['deductible_vat'] ?? 0,
                'vat_due' => $taxCalc['vat']['vat_due'],
                'income_tax_advance' => $taxCalc['totals']['income_tax_due'] ?? 0,
                'zus_social' => $taxCalc['zus']['social_contributions'],
                'zus_health' => $taxCalc['zus']['health_insurance'],
                'zus_total' => $taxCalc['zus']['total_zus'],
                'total_tax_burden' => $taxCalc['totals']['total_tax_burden'],
                'net_income_after_tax' => $taxCalc['totals']['net_income_after_all'],
            ]
        );

        Log::info('Monthly tax report generated', [
            'year' => $year,
            'month' => $month,
            'regime' => $regime->value,
            'revenue' => $revenue['total_gross'],
            'tax_burden' => $taxCalc['totals']['total_tax_burden'],
        ]);

        return $report;
    }

    // =====================================================
    // 2. VAT REPORT (JPK_VAT data)
    // =====================================================

    public function generateVatReport(int $year, int $month, int $userId): TaxReport
    {
        $config = config('polish_tax');
        $revenue = $this->getMonthlyRevenue($year, $month);
        $expenses = Expense::monthlySummary($year, $month);

        $reportData = [
            'company' => $config['company'],
            'period' => ['year' => $year, 'month' => $month],
            'type' => 'JPK_VAT',

            'sales' => [
                'total_net' => $revenue['total_net'],
                'total_vat' => $revenue['total_vat_collected'],
                'total_gross' => $revenue['total_gross'],
                'by_vat_rate' => $revenue['by_vat_rate'],
                'gtu_codes' => [$config['vat']['default_gtu']],
            ],

            'purchases' => [
                'total_net' => $expenses['total_net'],
                'total_vat_input' => $expenses['total_vat'],
                'total_gross' => $expenses['total_gross'],
            ],

            'settlement' => [
                'vat_output' => $revenue['total_vat_collected'],
                'vat_input' => $expenses['deductible_vat'],
                'vat_due' => max(0, $revenue['total_vat_collected'] - $expenses['deductible_vat']),
                'vat_refund' => max(0, $expenses['deductible_vat'] - $revenue['total_vat_collected']),
            ],

            'deadline' => sprintf('%04d-%02d-%02d', $year, $month + 1 > 12 ? 1 : $month + 1, $config['deadlines']['jpk_vat_monthly']),
        ];

        $lineItems = $this->getMonthlyLineItems($year, $month);

        return TaxReport::create([
            'report_type' => TaxReport::TYPE_JPK_VAT,
            'year' => $year,
            'month' => $month,
            'tax_regime' => config('polish_tax.company.tax_regime'),
            'report_data' => $reportData,
            'line_items' => $lineItems,
            'total_revenue' => $revenue['total_gross'],
            'total_costs' => $expenses['total_gross'],
            'total_vat' => max(0, $revenue['total_vat_collected'] - $expenses['deductible_vat']),
            'status' => 'generated',
            'generated_at' => now(),
            'generated_by' => $userId,
        ]);
    }

    // =====================================================
    // 3. ZUS REPORT (DRA declaration data)
    // =====================================================

    public function generateZusReport(
        int $year,
        int $month,
        TaxRegimeEnum $regime,
        int $userId,
        string $zusStage = 'full'
    ): TaxReport {
        $revenue = $this->getMonthlyRevenue($year, $month);
        $expenses = Expense::monthlySummary($year, $month);
        $income = $revenue['total_net'] - ($expenses['deductible_net'] ?? 0);

        $zus = $this->calculator->calculateZus($regime, $income, $zusStage);

        $reportData = [
            'company' => config('polish_tax.company'),
            'period' => ['year' => $year, 'month' => $month],
            'type' => 'ZUS DRA',
            'zus_stage' => $zusStage,
            'regime' => $regime->value,
            'monthly_income' => $income,
            'contributions' => $zus,
            'deadline' => sprintf('%04d-%02d-%02d', $year, $month + 1 > 12 ? 1 : $month + 1, config('polish_tax.zus.payment_deadline_jdg')),
        ];

        return TaxReport::create([
            'report_type' => TaxReport::TYPE_ZUS_DRA,
            'year' => $year,
            'month' => $month,
            'tax_regime' => $regime,
            'report_data' => $reportData,
            'total_zus' => $zus['total_zus'],
            'status' => 'generated',
            'generated_at' => now(),
            'generated_by' => $userId,
        ]);
    }

    // =====================================================
    // 4. ANNUAL TAX DECLARATION
    // =====================================================

    public function generateAnnualReport(
        int $year,
        TaxRegimeEnum $regime,
        int $userId,
        float $ryczaltRate = 15.0
    ): TaxReport {
        $cumulative = AccountingPeriod::cumulativeForYear($year);

        $annualCalc = $this->calculator->calculateAnnualSummary(
            regime: $regime,
            annualRevenue: $cumulative['gross_revenue'],
            annualCosts: $cumulative['total_costs'],
            annualVatOutput: $cumulative['vat_output'],
            annualVatInput: $cumulative['vat_input'],
            annualZusSocial: $cumulative['zus_social'],
            annualZusHealth: $cumulative['zus_health'],
            annualPitAdvances: $cumulative['income_tax_advances'],
            ryczaltRate: $ryczaltRate
        );

        $reportData = [
            'company' => config('polish_tax.company'),
            'year' => $year,
            'regime' => $regime->value,
            'pit_form' => $regime->pitForm(),
            'annual_calculation' => $annualCalc,
            'monthly_breakdown' => AccountingPeriod::forYear($year)
                ->orderBy('month')
                ->get()
                ->toArray(),
        ];

        return TaxReport::create([
            'report_type' => $regime === TaxRegimeEnum::RYCZALT
                ? TaxReport::TYPE_RYCZALT_ANNUAL
                : TaxReport::TYPE_PIT_ANNUAL,
            'year' => $year,
            'tax_regime' => $regime,
            'report_data' => $reportData,
            'total_revenue' => $cumulative['gross_revenue'],
            'total_costs' => $cumulative['total_costs'],
            'total_tax' => $annualCalc['income_tax']['annual_tax_due'],
            'total_vat' => $cumulative['vat_due'],
            'total_zus' => $cumulative['zus_total'],
            'status' => 'generated',
            'generated_at' => now(),
            'generated_by' => $userId,
        ]);
    }

    // =====================================================
    // 5. PROFIT & LOSS REPORT (internal)
    // =====================================================

    public function generateProfitLoss(int $year, int $month, int $userId): TaxReport
    {
        $revenue = $this->getMonthlyRevenue($year, $month);
        $expenses = Expense::monthlySummary($year, $month);

        $grossProfit = $revenue['total_net'] - ($expenses['total_net'] ?? 0);

        $reportData = [
            'period' => ['year' => $year, 'month' => $month],
            'revenue' => $revenue,
            'expenses' => $expenses,
            'gross_profit' => $grossProfit,
            'profit_margin' => $revenue['total_net'] > 0
                ? round(($grossProfit / $revenue['total_net']) * 100, 1) : 0,
        ];

        return TaxReport::create([
            'report_type' => TaxReport::TYPE_PROFIT_LOSS,
            'year' => $year,
            'month' => $month,
            'report_data' => $reportData,
            'total_revenue' => $revenue['total_gross'],
            'total_costs' => $expenses['total_gross'] ?? 0,
            'status' => 'generated',
            'generated_at' => now(),
            'generated_by' => $userId,
        ]);
    }

    // =====================================================
    // DATA GATHERING HELPERS
    // =====================================================

    /**
     * Get monthly revenue from both CRM invoices and POS transactions.
     */
    protected function getMonthlyRevenue(int $year, int $month): array
    {
        // POS invoiced transactions
        $pos = PosTransaction::where('status', 'invoiced')
            ->whereYear('decided_at', $year)
            ->whereMonth('decided_at', $month)
            ->get();

        // CRM invoices (non-POS)
        $invoices = Invoice::whereYear('issued_at', $year)
            ->whereMonth('issued_at', $month)
            ->where('status', 'paid')
            ->whereNull('pos_receipt_number')
            ->get();

        $totalGross = $pos->sum('amount') + $invoices->sum('amount');
        $totalNet = $pos->sum('net_amount') + $invoices->sum('net_amount');
        $totalVat = $pos->sum('tax_amount') + $invoices->sum('tax_amount');

        // By service type
        $byService = $pos->groupBy('service_type')->map(function ($group) {
            return [
                'count' => $group->count(),
                'gross' => $group->sum('amount'),
                'net' => $group->sum('net_amount'),
                'vat' => $group->sum('tax_amount'),
            ];
        })->toArray();

        // By payment method
        $byMethod = $pos->groupBy('payment_method')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount'),
            ];
        })->toArray();

        // By VAT rate
        $vatRate23 = $totalVat;
        $byVatRate = [
            '23' => ['net' => $totalNet, 'vat' => $vatRate23, 'gross' => $totalGross],
        ];

        return [
            'total_gross' => round($totalGross, 2),
            'total_net' => round($totalNet, 2),
            'total_vat_collected' => round($totalVat, 2),
            'invoices_count' => $invoices->count(),
            'pos_count' => $pos->count(),
            'by_service' => $byService,
            'by_method' => $byMethod,
            'by_vat_rate' => $byVatRate,
        ];
    }

    /**
     * Get individual line items for report detail.
     */
    protected function getMonthlyLineItems(int $year, int $month): array
    {
        $items = [];

        // POS transactions
        $pos = PosTransaction::where('status', 'invoiced')
            ->whereYear('decided_at', $year)
            ->whereMonth('decided_at', $month)
            ->orderBy('decided_at')
            ->get();

        foreach ($pos as $tx) {
            $items[] = [
                'type' => 'pos',
                'date' => $tx->decided_at->format('Y-m-d'),
                'document' => $tx->receipt_number,
                'client' => $tx->client_name,
                'service' => $tx->service_type?->value,
                'net' => $tx->net_amount,
                'vat_rate' => $tx->tax_rate,
                'vat' => $tx->tax_amount,
                'gross' => $tx->amount,
                'payment_method' => $tx->payment_method?->value,
            ];
        }

        // Regular invoices
        $invoices = Invoice::whereYear('issued_at', $year)
            ->whereMonth('issued_at', $month)
            ->where('status', 'paid')
            ->whereNull('pos_receipt_number')
            ->orderBy('issued_at')
            ->get();

        foreach ($invoices as $inv) {
            $items[] = [
                'type' => 'invoice',
                'date' => $inv->issued_at->format('Y-m-d'),
                'document' => $inv->invoice_number,
                'client' => $inv->client?->name ?? 'N/A',
                'service' => $inv->description,
                'net' => $inv->net_amount,
                'vat_rate' => $inv->tax_rate ?? 23,
                'vat' => $inv->tax_amount,
                'gross' => $inv->amount,
                'payment_method' => 'invoice',
            ];
        }

        // Expenses (for VAT input)
        $expenses = Expense::forMonth($year, $month)->orderBy('date')->get();

        foreach ($expenses as $exp) {
            $items[] = [
                'type' => 'expense',
                'date' => $exp->date->format('Y-m-d'),
                'document' => $exp->invoice_number ?? 'N/A',
                'vendor' => $exp->vendor,
                'vendor_nip' => $exp->vendor_nip,
                'category' => $exp->category,
                'net' => -$exp->net_amount,
                'vat_rate' => $exp->vat_rate?->rate(),
                'vat' => -$exp->vat_amount,
                'gross' => -$exp->gross_amount,
                'deductible' => $exp->is_tax_deductible,
            ];
        }

        return $items;
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Сервис TaxReportService — генерация всех отчётов для налоговой.
//
// ОТЧЁТЫ:
//   1. generateMonthlyTaxReport() — ПОЛНЫЙ месячный отчёт:
//      PIT/CIT аванс + VAT + ZUS. Собирает данные из POS-транзакций,
//      invoices CRM и расходов. Считает все налоги через TaxCalculatorService.
//      Создаёт/обновляет AccountingPeriod.
//
//   2. generateVatReport() — данные для JPK_VAT:
//      Продажи по ставкам VAT, покупки (VAT input), расчёт VAT due/refund.
//      GTU коды для иммиграционных услуг (GTU_12).
//
//   3. generateZusReport() — декларация ZUS DRA:
//      Социальные взносы + здоровье, с учётом стадии (ulga/льготный/полный).
//
//   4. generateAnnualReport() — годовая декларация PIT:
//      Кумулятивные данные за год, итоговый расчёт, переплата/недоплата.
//      Формы: PIT-36 (skala), PIT-36L (liniowy), PIT-28 (рычалт).
//
//   5. generateProfitLoss() — P&L для внутреннего использования.
//
// ИСТОЧНИКИ ДАННЫХ:
//   - POS transactions (status = invoiced) — оплаты в офисе
//   - Invoices (status = paid, no POS receipt) — обычные счета
//   - Expenses — расходы (для вычета и VAT input)
//
// LINE ITEMS: построчная детализация всех транзакций месяца.
// Файл: app/Services/Accounting/TaxReportService.php
// ---------------------------------------------------------------
