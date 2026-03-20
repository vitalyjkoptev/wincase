<?php

namespace App\Enums;

enum TaxRegimeEnum: string
{
    // --- JDG (Sole Proprietor) Tax Forms ---
    case SKALA_PODATKOWA = 'skala_podatkowa';
    case LINIOWY = 'liniowy';
    case RYCZALT = 'ryczalt';

    // --- Sp. z o.o. (LLC) ---
    case CIT_STANDARD = 'cit_standard';
    case CIT_SMALL = 'cit_small';
    case CIT_ESTONIAN = 'cit_estonian';

    public function label(): string
    {
        return match ($this) {
            self::SKALA_PODATKOWA => 'Skala podatkowa (Progressive 12%/32%)',
            self::LINIOWY => 'Podatek liniowy (Flat 19%)',
            self::RYCZALT => 'Ryczałt (Lump-sum on revenue)',
            self::CIT_STANDARD => 'CIT Standard (19%)',
            self::CIT_SMALL => 'CIT Small Taxpayer (9%)',
            self::CIT_ESTONIAN => 'CIT Estonian (tax on distribution)',
        };
    }

    public function labelPl(): string
    {
        return match ($this) {
            self::SKALA_PODATKOWA => 'Skala podatkowa (Zasady ogólne)',
            self::LINIOWY => 'Podatek liniowy 19%',
            self::RYCZALT => 'Ryczałt od przychodów ewidencjonowanych',
            self::CIT_STANDARD => 'CIT 19% (standardowy)',
            self::CIT_SMALL => 'CIT 9% (mały podatnik)',
            self::CIT_ESTONIAN => 'CIT estoński (ryczałt od dochodów)',
        };
    }

    public function isJdg(): bool
    {
        return in_array($this, [
            self::SKALA_PODATKOWA,
            self::LINIOWY,
            self::RYCZALT,
        ]);
    }

    public function isCorporate(): bool
    {
        return in_array($this, [
            self::CIT_STANDARD,
            self::CIT_SMALL,
            self::CIT_ESTONIAN,
        ]);
    }

    public function pitForm(): ?string
    {
        return match ($this) {
            self::SKALA_PODATKOWA => 'PIT-36',
            self::LINIOWY => 'PIT-36L',
            self::RYCZALT => 'PIT-28',
            default => null,
        };
    }

    public function healthInsuranceRate(): float
    {
        return match ($this) {
            self::SKALA_PODATKOWA => 9.00,
            self::LINIOWY => 4.90,
            self::RYCZALT => 0.00,
            default => 0.00,
        };
    }

    /**
     * Can health insurance be tax-deductible?
     */
    public function healthInsuranceDeductible(): bool
    {
        return match ($this) {
            self::SKALA_PODATKOWA => false,
            self::LINIOWY => true,
            self::RYCZALT => true,
            default => false,
        };
    }

    /**
     * Can business expenses be deducted?
     */
    public function allowsCostDeduction(): bool
    {
        return match ($this) {
            self::RYCZALT => false,
            default => true,
        };
    }

    /**
     * Tax-free allowance (kwota wolna od podatku).
     */
    public function taxFreeAllowance(): float
    {
        return match ($this) {
            self::SKALA_PODATKOWA => 30000.00,
            default => 0.00,
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum режимов налогообложения в Польше (6 значений).
// JDG (ИП): skala_podatkowa (12%/32%), liniowy (19%), ryczałt (по выручке).
// Sp. z o.o. (ООО): CIT 19%, CIT 9% (mały podatnik), CIT estoński.
// Методы: pitForm() — номер декларации, healthInsuranceRate() — ставка NFZ,
// allowsCostDeduction() — можно ли вычитать расходы,
// taxFreeAllowance() — необлагаемый минимум (30 000 PLN для skala).
// Файл: app/Enums/TaxRegimeEnum.php
// ---------------------------------------------------------------
