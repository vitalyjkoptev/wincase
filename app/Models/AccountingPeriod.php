<?php

namespace App\Models;

use App\Enums\TaxRegimeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'period_type',
        'tax_regime',
        'gross_revenue',
        'net_revenue',
        'total_costs',
        'taxable_income',
        'vat_output',
        'vat_input',
        'vat_due',
        'income_tax_advance',
        'income_tax_cumulative',
        'zus_social',
        'zus_health',
        'zus_total',
        'total_tax_burden',
        'net_income_after_tax',
        'status',
        'closed_at',
        'closed_by',
    ];

    protected function casts(): array
    {
        return [
            'tax_regime' => TaxRegimeEnum::class,
            'gross_revenue' => 'decimal:2',
            'net_revenue' => 'decimal:2',
            'total_costs' => 'decimal:2',
            'taxable_income' => 'decimal:2',
            'vat_output' => 'decimal:2',
            'vat_input' => 'decimal:2',
            'vat_due' => 'decimal:2',
            'income_tax_advance' => 'decimal:2',
            'income_tax_cumulative' => 'decimal:2',
            'zus_social' => 'decimal:2',
            'zus_health' => 'decimal:2',
            'zus_total' => 'decimal:2',
            'total_tax_burden' => 'decimal:2',
            'net_income_after_tax' => 'decimal:2',
            'closed_at' => 'datetime',
        ];
    }

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    public function closedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getPeriodLabelAttribute(): string
    {
        return sprintf('%04d-%02d', $this->year, $this->month);
    }

    public function getEffectiveTaxRateAttribute(): float
    {
        return $this->gross_revenue > 0
            ? round(($this->total_tax_burden / $this->gross_revenue) * 100, 1)
            : 0;
    }

    public function getIsCurrentMonthAttribute(): bool
    {
        return $this->year === (int) now()->format('Y')
            && $this->month === (int) now()->format('m');
    }

    // =====================================================
    // METHODS
    // =====================================================

    public function close(int $userId): void
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closed_by' => $userId,
        ]);
    }

    public function reopen(): void
    {
        $this->update([
            'status' => 'open',
            'closed_at' => null,
            'closed_by' => null,
        ]);
    }

    // =====================================================
    // STATIC: Cumulative totals for year-to-date
    // =====================================================

    public static function cumulativeForYear(int $year, ?int $upToMonth = null): array
    {
        $query = static::where('year', $year);

        if ($upToMonth) {
            $query->where('month', '<=', $upToMonth);
        }

        return [
            'year' => $year,
            'up_to_month' => $upToMonth,
            'gross_revenue' => $query->sum('gross_revenue'),
            'net_revenue' => $query->sum('net_revenue'),
            'total_costs' => $query->sum('total_costs'),
            'taxable_income' => $query->sum('taxable_income'),
            'vat_output' => $query->sum('vat_output'),
            'vat_input' => $query->sum('vat_input'),
            'vat_due' => $query->sum('vat_due'),
            'income_tax_advances' => $query->sum('income_tax_advance'),
            'zus_social' => $query->sum('zus_social'),
            'zus_health' => $query->sum('zus_health'),
            'zus_total' => $query->sum('zus_total'),
            'total_tax_burden' => $query->sum('total_tax_burden'),
            'net_income_after_tax' => $query->sum('net_income_after_tax'),
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Модель AccountingPeriod — месячные бухгалтерские периоды.
// Хранит все расчёты за месяц: выручка, расходы, VAT, PIT/CIT аванс,
// ZUS, общая налоговая нагрузка, чистый доход после налогов.
// Статусы: open (можно редактировать), closed (закрыт владельцем).
// cumulativeForYear() — кумулятивные итоги с начала года.
// Файл: app/Models/AccountingPeriod.php
// ---------------------------------------------------------------
