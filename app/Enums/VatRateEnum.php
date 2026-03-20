<?php

namespace App\Enums;

enum VatRateEnum: string
{
    case STANDARD_23 = '23';
    case REDUCED_8 = '8';
    case SUPER_REDUCED_5 = '5';
    case ZERO_0 = '0';
    case EXEMPT = 'zw';
    case NOT_APPLICABLE = 'np';

    public function label(): string
    {
        return match ($this) {
            self::STANDARD_23 => 'VAT 23% (standard)',
            self::REDUCED_8 => 'VAT 8% (reduced)',
            self::SUPER_REDUCED_5 => 'VAT 5% (super-reduced)',
            self::ZERO_0 => 'VAT 0% (zero-rated)',
            self::EXEMPT => 'VAT zwolniony (exempt)',
            self::NOT_APPLICABLE => 'VAT nie podlega (N/A)',
        };
    }

    public function rate(): float
    {
        return match ($this) {
            self::STANDARD_23 => 23.00,
            self::REDUCED_8 => 8.00,
            self::SUPER_REDUCED_5 => 5.00,
            self::ZERO_0 => 0.00,
            self::EXEMPT => 0.00,
            self::NOT_APPLICABLE => 0.00,
        };
    }

    public function calculateNet(float $grossAmount): float
    {
        if ($this->rate() === 0.0) {
            return $grossAmount;
        }

        return round($grossAmount / (1 + $this->rate() / 100), 2);
    }

    public function calculateVat(float $grossAmount): float
    {
        return round($grossAmount - $this->calculateNet($grossAmount), 2);
    }

    public function calculateGross(float $netAmount): float
    {
        return round($netAmount * (1 + $this->rate() / 100), 2);
    }

    /**
     * Typical services/goods for each VAT rate in Poland.
     */
    public function typicalUsage(): string
    {
        return match ($this) {
            self::STANDARD_23 => 'Legal consulting, immigration services, business consulting, IT services, marketing, office rent',
            self::REDUCED_8 => 'Construction services (residential), restaurant food (on-site), transport, hotel services',
            self::SUPER_REDUCED_5 => 'Books, periodicals, basic food products, social housing',
            self::ZERO_0 => 'Intra-EU supplies, export of goods, international transport',
            self::EXEMPT => 'Financial services, insurance, medical services, education, postal services',
            self::NOT_APPLICABLE => 'Non-VAT payer transactions, private sales',
        };
    }

    /**
     * GTU (Grupa Towarów i Usług) codes for JPK_VAT reporting.
     * Immigration bureau uses mainly GTU_12 (consulting/legal).
     */
    public function gtuCodes(): array
    {
        return match ($this) {
            self::STANDARD_23 => ['GTU_12'],
            default => [],
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum ставок VAT в Польше (6 значений).
// 23% — стандартная (юридические, консалтинг, иммиграционные услуги).
// 8% — пониженная (строительство жилое, рестораны, отели, транспорт).
// 5% — сверхпониженная (книги, базовые продукты, социальное жильё).
// 0% — нулевая (экспорт, внутри-ЕС поставки, международный транспорт).
// zw — освобождён (финансы, медицина, образование, страхование).
// np — не подлежит (не плательщик VAT).
// Методы: calculateNet/Vat/Gross — расчёт нетто/VAT/брутто.
// gtuCodes() — коды GTU для JPK_VAT (иммиграция = GTU_12).
// Файл: app/Enums/VatRateEnum.php
// ---------------------------------------------------------------
