<?php

namespace App\Services\Accounting;

use App\Enums\TaxRegimeEnum;
use App\Enums\VatRateEnum;
use App\Enums\RyczaltRateEnum;

class TaxCalculatorService
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('polish_tax');
    }

    // =====================================================
    // VAT CALCULATION
    // =====================================================

    /**
     * Calculate VAT for a single transaction.
     */
    public function calculateVat(float $grossAmount, VatRateEnum $rate): array
    {
        $net = $rate->calculateNet($grossAmount);
        $vat = $rate->calculateVat($grossAmount);

        return [
            'gross' => round($grossAmount, 2),
            'net' => $net,
            'vat' => $vat,
            'rate' => $rate->rate(),
            'rate_label' => $rate->label(),
        ];
    }

    /**
     * Calculate monthly VAT due (output - input).
     */
    public function calculateMonthlyVat(float $vatOutput, float $vatInput): array
    {
        $vatDue = round($vatOutput - $vatInput, 2);

        return [
            'vat_output' => round($vatOutput, 2),
            'vat_input' => round($vatInput, 2),
            'vat_due' => max(0, $vatDue),
            'vat_refund' => $vatDue < 0 ? abs($vatDue) : 0,
            'deadline_day' => $this->config['vat']['jpk_vat_deadline'],
        ];
    }

    // =====================================================
    // PIT — Skala Podatkowa (Progressive 12%/32%)
    // =====================================================

    /**
     * Calculate PIT advance for Skala Podatkowa.
     * Uses cumulative method (income from Jan to current month).
     */
    public function calculatePitSkala(
        float $cumulativeIncome,
        float $cumulativeZusSocial,
        float $previousAdvances
    ): array {
        $cfg = $this->config['pit']['skala'];

        // Deduct ZUS social from income
        $taxableBase = max(0, $cumulativeIncome - $cumulativeZusSocial);

        // Apply tax-free allowance
        $taxFreeAmount = $cfg['tax_free_amount'];

        if ($taxableBase <= $cfg['threshold']) {
            $taxGross = $taxableBase * ($cfg['rate_lower'] / 100);
            $taxReducing = $cfg['tax_reducing_amount'];
        } else {
            $taxLower = $cfg['threshold'] * ($cfg['rate_lower'] / 100);
            $taxUpper = ($taxableBase - $cfg['threshold']) * ($cfg['rate_upper'] / 100);
            $taxGross = $taxLower + $taxUpper;
            $taxReducing = $cfg['tax_reducing_amount'];
        }

        $taxAfterReducing = max(0, $taxGross - $taxReducing);
        $advanceDue = max(0, round($taxAfterReducing - $previousAdvances, 0));

        return [
            'regime' => 'skala_podatkowa',
            'cumulative_income' => $cumulativeIncome,
            'zus_social_deduction' => $cumulativeZusSocial,
            'taxable_base' => round($taxableBase, 2),
            'tax_free_amount' => $taxFreeAmount,
            'rate_applied' => $taxableBase <= $cfg['threshold'] ? $cfg['rate_lower'] : $cfg['rate_upper'],
            'tax_gross' => round($taxGross, 2),
            'tax_reducing_amount' => $taxReducing,
            'tax_after_reducing' => round($taxAfterReducing, 2),
            'previous_advances' => $previousAdvances,
            'advance_due' => $advanceDue,
            'deadline_day' => $this->config['pit']['advance_payment_deadline'],
        ];
    }

    // =====================================================
    // PIT — Liniowy (Flat 19%)
    // =====================================================

    public function calculatePitLiniowy(
        float $cumulativeIncome,
        float $cumulativeZusSocial,
        float $cumulativeHealthDeductible,
        float $previousAdvances
    ): array {
        $rate = $this->config['pit']['liniowy']['rate'];

        $taxableBase = max(0, $cumulativeIncome - $cumulativeZusSocial - $cumulativeHealthDeductible);
        $taxGross = $taxableBase * ($rate / 100);
        $advanceDue = max(0, round($taxGross - $previousAdvances, 0));

        return [
            'regime' => 'liniowy',
            'cumulative_income' => $cumulativeIncome,
            'zus_social_deduction' => $cumulativeZusSocial,
            'health_deduction' => $cumulativeHealthDeductible,
            'taxable_base' => round($taxableBase, 2),
            'rate' => $rate,
            'tax_gross' => round($taxGross, 2),
            'previous_advances' => $previousAdvances,
            'advance_due' => $advanceDue,
            'deadline_day' => $this->config['pit']['advance_payment_deadline'],
        ];
    }

    // =====================================================
    // PIT — Ryczałt (Lump-sum on revenue)
    // =====================================================

    /**
     * Calculate Ryczałt tax. Tax is on REVENUE (not profit).
     * No cost deductions allowed.
     */
    public function calculateRyczalt(
        float $monthlyRevenue,
        float $ryczaltRate = 15.0,
        float $previousAdvances = 0.0,
        float $cumulativeRevenue = 0.0
    ): array {
        $tax = round($monthlyRevenue * ($ryczaltRate / 100), 0);

        // Health insurance for ryczałt (fixed by bracket)
        $annualRevenue = $cumulativeRevenue + $monthlyRevenue;
        $healthMonthly = $this->getHealthInsuranceRyczalt($annualRevenue);

        // Health insurance IS deductible for ryczałt (50%)
        $healthDeductible = round($healthMonthly * 0.50, 2);
        $taxAfterDeduction = max(0, $tax - $healthDeductible);

        return [
            'regime' => 'ryczalt',
            'monthly_revenue' => $monthlyRevenue,
            'ryczalt_rate' => $ryczaltRate,
            'tax_before_deduction' => $tax,
            'health_insurance_monthly' => $healthMonthly,
            'health_deductible_50pct' => $healthDeductible,
            'tax_after_deduction' => round($taxAfterDeduction, 0),
            'cumulative_revenue' => $annualRevenue,
            'deadline_day' => $this->config['pit']['advance_payment_deadline'],
        ];
    }

    // =====================================================
    // CIT — Corporate Income Tax
    // =====================================================

    public function calculateCit(
        float $cumulativeIncome,
        float $previousAdvances,
        bool $isSmallTaxpayer = false
    ): array {
        $rate = $isSmallTaxpayer
            ? $this->config['cit']['small_taxpayer_rate']
            : $this->config['cit']['standard_rate'];

        $taxGross = round($cumulativeIncome * ($rate / 100), 2);
        $advanceDue = max(0, round($taxGross - $previousAdvances, 0));

        return [
            'regime' => $isSmallTaxpayer ? 'cit_small' : 'cit_standard',
            'cumulative_income' => $cumulativeIncome,
            'rate' => $rate,
            'tax_gross' => $taxGross,
            'previous_advances' => $previousAdvances,
            'advance_due' => $advanceDue,
        ];
    }

    // =====================================================
    // ZUS — Social Security Contributions
    // =====================================================

    /**
     * Calculate monthly ZUS contributions.
     */
    public function calculateZus(
        TaxRegimeEnum $regime,
        float $monthlyIncome = 0.0,
        string $zusStage = 'full'
    ): array {
        $zusCfg = $this->config['zus'];

        // Social contributions
        $socialContributions = match ($zusStage) {
            'ulga_na_start' => 0.00,
            'preferential' => $zusCfg['preferential']['total_social_monthly'],
            default => $zusCfg['total_social_monthly'],
        };

        // Health insurance
        $healthInsurance = match (true) {
            $regime === TaxRegimeEnum::SKALA_PODATKOWA => max(
                $zusCfg['health']['minimum_monthly'],
                round($monthlyIncome * ($zusCfg['health']['skala_rate'] / 100), 2)
            ),
            $regime === TaxRegimeEnum::LINIOWY => max(
                $zusCfg['health']['minimum_monthly'],
                round($monthlyIncome * ($zusCfg['health']['liniowy_rate'] / 100), 2)
            ),
            $regime === TaxRegimeEnum::RYCZALT => $this->getHealthInsuranceRyczalt(
                $monthlyIncome * 12
            ),
            default => 0.00,
        };

        $totalZus = round($socialContributions + $healthInsurance, 2);

        return [
            'zus_stage' => $zusStage,
            'social_contributions' => $socialContributions,
            'health_insurance' => $healthInsurance,
            'health_rate' => $regime->healthInsuranceRate(),
            'health_deductible' => $regime->healthInsuranceDeductible(),
            'total_zus' => $totalZus,
            'deadline_day' => $zusCfg['payment_deadline_jdg'],
            'breakdown' => $zusStage === 'full' ? [
                'pension' => round($zusCfg['base_full'] * $zusCfg['pension']['rate'] / 100, 2),
                'disability' => round($zusCfg['base_full'] * $zusCfg['disability']['rate'] / 100, 2),
                'sickness' => round($zusCfg['base_full'] * $zusCfg['sickness']['rate'] / 100, 2),
                'accident' => round($zusCfg['base_full'] * $zusCfg['accident']['rate'] / 100, 2),
                'labor_fund' => round($zusCfg['base_full'] * $zusCfg['labor_fund']['rate'] / 100, 2),
                'fgsp' => round($zusCfg['base_full'] * $zusCfg['fgsp']['rate'] / 100, 2),
            ] : null,
        ];
    }

    // =====================================================
    // FULL MONTHLY CALCULATION
    // =====================================================

    /**
     * Complete monthly tax calculation — all taxes combined.
     */
    public function calculateMonthlyTotal(
        TaxRegimeEnum $regime,
        float $monthlyGrossRevenue,
        float $monthlyCosts,
        float $vatOutput,
        float $vatInput,
        float $cumulativeIncome,
        float $previousPitAdvances,
        string $zusStage = 'full',
        float $ryczaltRate = 15.0
    ): array {
        // 1. VAT
        $vat = $this->calculateMonthlyVat($vatOutput, $vatInput);

        // 2. ZUS
        $monthlyIncome = $monthlyGrossRevenue - $monthlyCosts;
        $zus = $this->calculateZus($regime, $monthlyIncome, $zusStage);

        // 3. Income Tax (depends on regime)
        $pit = match ($regime) {
            TaxRegimeEnum::SKALA_PODATKOWA => $this->calculatePitSkala(
                $cumulativeIncome + $monthlyIncome,
                $zus['social_contributions'],
                $previousPitAdvances
            ),
            TaxRegimeEnum::LINIOWY => $this->calculatePitLiniowy(
                $cumulativeIncome + $monthlyIncome,
                $zus['social_contributions'],
                $zus['health_deductible'] ? $zus['health_insurance'] : 0,
                $previousPitAdvances
            ),
            TaxRegimeEnum::RYCZALT => $this->calculateRyczalt(
                $monthlyGrossRevenue,
                $ryczaltRate,
                $previousPitAdvances,
                $cumulativeIncome
            ),
            TaxRegimeEnum::CIT_STANDARD => $this->calculateCit(
                $cumulativeIncome + $monthlyIncome, $previousPitAdvances, false
            ),
            TaxRegimeEnum::CIT_SMALL => $this->calculateCit(
                $cumulativeIncome + $monthlyIncome, $previousPitAdvances, true
            ),
            default => ['advance_due' => 0, 'regime' => $regime->value],
        };

        // 4. Total burden
        $totalTaxBurden = round(
            ($pit['advance_due'] ?? $pit['tax_after_deduction'] ?? 0)
            + $vat['vat_due']
            + $zus['total_zus'],
            2
        );

        $netAfterEverything = round($monthlyGrossRevenue - $monthlyCosts - $totalTaxBurden, 2);

        return [
            'period' => [
                'regime' => $regime->value,
                'regime_label' => $regime->label(),
                'gross_revenue' => $monthlyGrossRevenue,
                'costs' => $monthlyCosts,
                'net_income_before_tax' => round($monthlyIncome, 2),
            ],
            'vat' => $vat,
            'income_tax' => $pit,
            'zus' => $zus,
            'totals' => [
                'income_tax_due' => $pit['advance_due'] ?? $pit['tax_after_deduction'] ?? 0,
                'vat_due' => $vat['vat_due'],
                'zus_total' => $zus['total_zus'],
                'total_tax_burden' => $totalTaxBurden,
                'net_income_after_all' => $netAfterEverything,
                'effective_tax_rate' => $monthlyGrossRevenue > 0
                    ? round(($totalTaxBurden / $monthlyGrossRevenue) * 100, 1)
                    : 0,
            ],
            'deadlines' => [
                'vat_jpk' => $this->config['vat']['jpk_vat_deadline'],
                'pit_advance' => $this->config['pit']['advance_payment_deadline'],
                'zus' => $this->config['zus']['payment_deadline_jdg'],
            ],
        ];
    }

    // =====================================================
    // ANNUAL SUMMARY
    // =====================================================

    /**
     * Generate annual tax summary for filing.
     */
    public function calculateAnnualSummary(
        TaxRegimeEnum $regime,
        float $annualRevenue,
        float $annualCosts,
        float $annualVatOutput,
        float $annualVatInput,
        float $annualZusSocial,
        float $annualZusHealth,
        float $annualPitAdvances,
        float $ryczaltRate = 15.0
    ): array {
        $annualIncome = $annualRevenue - $annualCosts;

        // Recalculate full year tax
        $finalTax = match ($regime) {
            TaxRegimeEnum::SKALA_PODATKOWA => $this->calculatePitSkala($annualIncome, $annualZusSocial, 0),
            TaxRegimeEnum::LINIOWY => $this->calculatePitLiniowy($annualIncome, $annualZusSocial, $annualZusHealth, 0),
            TaxRegimeEnum::RYCZALT => $this->calculateRyczalt($annualRevenue, $ryczaltRate, 0, 0),
            TaxRegimeEnum::CIT_STANDARD => $this->calculateCit($annualIncome, 0, false),
            TaxRegimeEnum::CIT_SMALL => $this->calculateCit($annualIncome, 0, true),
            default => ['tax_gross' => 0, 'tax_after_deduction' => 0],
        };

        $annualTaxDue = $finalTax['tax_gross'] ?? $finalTax['tax_before_deduction'] ?? 0;
        $overpaid = round($annualPitAdvances - $annualTaxDue, 2);

        return [
            'year' => $this->config['tax_year'],
            'regime' => $regime->value,
            'pit_form' => $regime->pitForm(),
            'revenue' => [
                'gross_revenue' => $annualRevenue,
                'costs' => $annualCosts,
                'net_income' => $annualIncome,
            ],
            'vat' => [
                'total_output' => $annualVatOutput,
                'total_input' => $annualVatInput,
                'total_paid' => round($annualVatOutput - $annualVatInput, 2),
            ],
            'income_tax' => [
                'annual_tax_due' => round($annualTaxDue, 2),
                'advances_paid' => $annualPitAdvances,
                'overpaid' => max(0, $overpaid),
                'underpaid' => max(0, -$overpaid),
                'calculation' => $finalTax,
            ],
            'zus' => [
                'total_social' => $annualZusSocial,
                'total_health' => $annualZusHealth,
                'total' => round($annualZusSocial + $annualZusHealth, 2),
            ],
            'grand_total' => [
                'total_taxes_paid' => round(
                    $annualPitAdvances
                    + ($annualVatOutput - $annualVatInput)
                    + $annualZusSocial
                    + $annualZusHealth,
                    2
                ),
                'effective_rate' => $annualRevenue > 0
                    ? round(((
                        $annualTaxDue
                        + ($annualVatOutput - $annualVatInput)
                        + $annualZusSocial
                        + $annualZusHealth
                    ) / $annualRevenue) * 100, 1)
                    : 0,
            ],
            'filing_deadline' => $regime->pitForm() === 'PIT-28'
                ? $this->config['deadlines']['pit28_annual']
                : $this->config['deadlines']['pit_annual'],
        ];
    }

    // =====================================================
    // TAX REGIME COMPARISON
    // =====================================================

    /**
     * Compare all tax regimes for a given revenue/costs.
     * Helps owner choose the optimal regime.
     */
    public function compareRegimes(
        float $annualRevenue,
        float $annualCosts,
        string $zusStage = 'full'
    ): array {
        $regimes = [
            TaxRegimeEnum::SKALA_PODATKOWA,
            TaxRegimeEnum::LINIOWY,
            TaxRegimeEnum::RYCZALT,
        ];

        $results = [];
        $income = $annualRevenue - $annualCosts;

        foreach ($regimes as $regime) {
            $zus = $this->calculateZus($regime, $income / 12, $zusStage);
            $annualZus = $zus['total_zus'] * 12;

            $taxCalc = match ($regime) {
                TaxRegimeEnum::SKALA_PODATKOWA => $this->calculatePitSkala(
                    $income, $zus['social_contributions'] * 12, 0
                ),
                TaxRegimeEnum::LINIOWY => $this->calculatePitLiniowy(
                    $income, $zus['social_contributions'] * 12,
                    $zus['health_insurance'] * 12, 0
                ),
                TaxRegimeEnum::RYCZALT => $this->calculateRyczalt(
                    $annualRevenue, 15.0, 0, 0
                ),
            };

            $annualTax = $taxCalc['tax_gross'] ?? $taxCalc['tax_before_deduction'] ?? 0;
            $totalBurden = round($annualTax + $annualZus, 2);
            $netAfter = round($annualRevenue - $annualCosts - $totalBurden, 2);

            $results[] = [
                'regime' => $regime->value,
                'label' => $regime->label(),
                'income_tax' => round($annualTax, 2),
                'zus_annual' => round($annualZus, 2),
                'total_burden' => $totalBurden,
                'net_after_all' => $netAfter,
                'effective_rate' => $annualRevenue > 0
                    ? round(($totalBurden / $annualRevenue) * 100, 1) : 0,
            ];
        }

        // Sort by net_after_all descending (best option first)
        usort($results, fn ($a, $b) => $b['net_after_all'] <=> $a['net_after_all']);

        return [
            'input' => [
                'annual_revenue' => $annualRevenue,
                'annual_costs' => $annualCosts,
                'annual_income' => $income,
                'zus_stage' => $zusStage,
            ],
            'comparison' => $results,
            'recommended' => $results[0]['regime'],
            'recommended_label' => $results[0]['label'],
        ];
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function getHealthInsuranceRyczalt(float $annualRevenue): float
    {
        $brackets = $this->config['zus']['health']['ryczalt_brackets'];

        foreach ($brackets as $bracket) {
            if ($annualRevenue <= $bracket['max_revenue']) {
                return $bracket['monthly'];
            }
        }

        return end($brackets)['monthly'];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Сервис TaxCalculatorService — полный расчёт ВСЕХ налогов Польши.
//
// РАСЧЁТЫ:
//   VAT: нетто/брутто/VAT для любой ставки, месячный VAT due.
//   PIT Skala: прогрессивный 12%/32% с кумулятивным методом,
//              вычет ZUS, необлагаемый минимум 30 000 PLN.
//   PIT Liniowy: фиксированный 19%, вычет ZUS + NFZ.
//   Рычалт: налог на ВЫРУЧКУ (не прибыль), вычет 50% NFZ.
//   CIT: стандартный 19% / малый 9%.
//   ZUS: полный / льготный / ulga na start,
//        здоровье: 9% (skala), 4.9% (liniowy), фиксированный (рычалт).
//
// КЛЮЧЕВЫЕ МЕТОДЫ:
//   calculateMonthlyTotal() — ПОЛНЫЙ месячный расчёт (VAT + PIT + ZUS).
//   calculateAnnualSummary() — годовой итог для декларации.
//   compareRegimes() — сравнение режимов (какой выгоднее).
//
// Все ставки берутся из config/polish_tax.php — обновляются ежегодно.
// Файл: app/Services/Accounting/TaxCalculatorService.php
// ---------------------------------------------------------------
