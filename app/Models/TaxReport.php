<?php

namespace App\Models;

use App\Enums\TaxRegimeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_type',
        'year',
        'month',
        'quarter',
        'tax_regime',
        'report_data',
        'line_items',
        'total_revenue',
        'total_costs',
        'total_tax',
        'total_vat',
        'total_zus',
        'status',
        'generated_at',
        'submitted_at',
        'generated_by',
        'notes',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'tax_regime' => TaxRegimeEnum::class,
            'report_data' => 'array',
            'line_items' => 'array',
            'total_revenue' => 'decimal:2',
            'total_costs' => 'decimal:2',
            'total_tax' => 'decimal:2',
            'total_vat' => 'decimal:2',
            'total_zus' => 'decimal:2',
            'generated_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    // =====================================================
    // REPORT TYPE CONSTANTS
    // =====================================================

    public const TYPE_JPK_VAT = 'jpk_vat';
    public const TYPE_PIT_ADVANCE = 'pit_advance';
    public const TYPE_PIT_ANNUAL = 'pit_annual';
    public const TYPE_RYCZALT_MONTHLY = 'ryczalt_monthly';
    public const TYPE_RYCZALT_ANNUAL = 'ryczalt_annual';
    public const TYPE_CIT_ADVANCE = 'cit_advance';
    public const TYPE_CIT_ANNUAL = 'cit_annual';
    public const TYPE_ZUS_DRA = 'zus_dra';
    public const TYPE_CASH_FLOW = 'cash_flow';
    public const TYPE_PROFIT_LOSS = 'profit_loss';
    public const TYPE_TAX_SUMMARY = 'tax_summary';

    public static function reportTypes(): array
    {
        return [
            self::TYPE_JPK_VAT => 'JPK_VAT (Jednolity Plik Kontrolny)',
            self::TYPE_PIT_ADVANCE => 'PIT Advance Payment (zaliczka)',
            self::TYPE_PIT_ANNUAL => 'PIT Annual Declaration',
            self::TYPE_RYCZALT_MONTHLY => 'Ryczałt Monthly Tax',
            self::TYPE_RYCZALT_ANNUAL => 'Ryczałt Annual (PIT-28)',
            self::TYPE_CIT_ADVANCE => 'CIT Advance Payment',
            self::TYPE_CIT_ANNUAL => 'CIT Annual Declaration',
            self::TYPE_ZUS_DRA => 'ZUS DRA Declaration',
            self::TYPE_CASH_FLOW => 'Cash Flow Report (internal)',
            self::TYPE_PROFIT_LOSS => 'Profit & Loss Report (internal)',
            self::TYPE_TAX_SUMMARY => 'Full Tax Summary (for accountant)',
        ];
    }

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    public function generatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeByType($query, string $type)
    {
        return $query->where('report_type', $type);
    }

    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeGenerated($query)
    {
        return $query->where('status', 'generated');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getReportTitleAttribute(): string
    {
        $types = self::reportTypes();
        $typeLabel = $types[$this->report_type] ?? $this->report_type;

        if ($this->month) {
            return "{$typeLabel} — {$this->year}/{$this->month}";
        }

        if ($this->quarter) {
            return "{$typeLabel} — {$this->year}/Q{$this->quarter}";
        }

        return "{$typeLabel} — {$this->year}";
    }

    public function getIsSubmittedAttribute(): bool
    {
        return $this->submitted_at !== null;
    }

    // =====================================================
    // METHODS
    // =====================================================

    public function markAsGenerated(int $userId): void
    {
        $this->update([
            'status' => 'generated',
            'generated_at' => now(),
            'generated_by' => $userId,
        ]);
    }

    public function markAsSubmitted(?string $notes = null): void
    {
        $this->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'notes' => $notes,
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Модель TaxReport — сгенерированные отчёты для налоговой.
// 11 типов: JPK_VAT, PIT advance, PIT annual, рычалт, CIT, ZUS DRA,
// Cash Flow, P&L, Tax Summary.
// Статусы: draft → generated → submitted (отправка вручную).
// report_data JSON — полные расчёты, line_items — построчная детализация.
// Файл: app/Models/TaxReport.php
// ---------------------------------------------------------------
