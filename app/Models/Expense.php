<?php

namespace App\Models;

use App\Enums\VatRateEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'category',
        'description',
        'vendor',
        'vendor_nip',
        'invoice_number',
        'net_amount',
        'vat_rate',
        'vat_amount',
        'gross_amount',
        'payment_method',
        'is_tax_deductible',
        'deductible_percentage',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'vat_rate' => VatRateEnum::class,
            'net_amount' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'gross_amount' => 'decimal:2',
            'is_tax_deductible' => 'boolean',
            'deductible_percentage' => 'decimal:2',
        ];
    }

    // =====================================================
    // EXPENSE CATEGORIES
    // =====================================================

    public const CATEGORIES = [
        'office_rent' => 'Office Rent (Czynsz biura)',
        'office_supplies' => 'Office Supplies (Art. biurowe)',
        'software' => 'Software & Subscriptions (Oprogramowanie)',
        'marketing' => 'Marketing & Advertising (Reklama)',
        'accounting' => 'Accounting Services (Księgowość)',
        'legal' => 'Legal Services (Usługi prawne)',
        'transport' => 'Transport & Fuel (Transport/Paliwo)',
        'communication' => 'Phone & Internet (Telefon/Internet)',
        'equipment' => 'Equipment (Sprzęt)',
        'bank_fees' => 'Bank Fees (Opłaty bankowe)',
        'insurance' => 'Insurance (Ubezpieczenia)',
        'training' => 'Training & Education (Szkolenia)',
        'hosting' => 'Hosting & Domains (Hosting/Domeny)',
        'pos_terminal' => 'POS Terminal Fees (Terminal płatniczy)',
        'other' => 'Other (Inne)',
    ];

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }

    public function scopeForYear($query, int $year)
    {
        return $query->whereYear('date', $year);
    }

    public function scopeDeductible($query)
    {
        return $query->where('is_tax_deductible', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getDeductibleAmountAttribute(): float
    {
        if (!$this->is_tax_deductible) {
            return 0;
        }

        return round($this->net_amount * ($this->deductible_percentage / 100), 2);
    }

    public function getDeductibleVatAttribute(): float
    {
        if (!$this->is_tax_deductible) {
            return 0;
        }

        return round($this->vat_amount * ($this->deductible_percentage / 100), 2);
    }

    // =====================================================
    // STATIC AGGREGATIONS
    // =====================================================

    public static function monthlySummary(int $year, int $month): array
    {
        $query = static::forMonth($year, $month);

        return [
            'total_net' => $query->sum('net_amount'),
            'total_vat' => $query->sum('vat_amount'),
            'total_gross' => $query->sum('gross_amount'),
            'deductible_net' => (clone $query)->deductible()->sum('net_amount'),
            'deductible_vat' => (clone $query)->deductible()->sum('vat_amount'),
            'by_category' => (clone $query)
                ->selectRaw('category, COUNT(*) as count, SUM(net_amount) as net, SUM(vat_amount) as vat, SUM(gross_amount) as gross')
                ->groupBy('category')
                ->get()
                ->toArray(),
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Модель Expense — записи расходов для налогового вычета.
// 15 категорий расходов (аренда, софт, маркетинг, бухгалтерия, POS и т.д.).
// Используется при режимах skala/liniowy/CIT (не при рычалте).
// deductibleAmount — сумма вычета с учётом % (например, 50% для авто).
// monthlySummary() — агрегация по категориям за месяц.
// Файл: app/Models/Expense.php
// ---------------------------------------------------------------
