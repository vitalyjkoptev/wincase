<?php

namespace App\Enums;

enum RyczaltRateEnum: string
{
    case RATE_17 = '17';
    case RATE_15 = '15';
    case RATE_14 = '14';
    case RATE_12 = '12';
    case RATE_10 = '10';
    case RATE_8_5 = '8.5';
    case RATE_5_5 = '5.5';
    case RATE_3 = '3';
    case RATE_2 = '2';

    public function rate(): float
    {
        return (float) $this->value;
    }

    public function label(): string
    {
        return match ($this) {
            self::RATE_17 => '17% — Wolne zawody (liberal professions — not regulated)',
            self::RATE_15 => '15% — Legal, consulting, bookkeeping, immigration services',
            self::RATE_14 => '14% — Healthcare services',
            self::RATE_12 => '12% — IT services, programming, software development',
            self::RATE_10 => '10% — Rental income (property)',
            self::RATE_8_5 => '8.5% — General services, liberal professions (regulated), education',
            self::RATE_5_5 => '5.5% — Construction, manufacturing',
            self::RATE_3 => '3% — Trade (retail/wholesale), gastronomy',
            self::RATE_2 => '2% — Sale of agricultural products',
        };
    }

    /**
     * PKD codes applicable to each ryczałt rate.
     * Immigration bureau: PKD 69.10.Z (legal), 69.20.Z (accounting),
     * 70.22.Z (business consulting) → 15% rate.
     */
    public function pkdExamples(): array
    {
        return match ($this) {
            self::RATE_17 => ['Wolne zawody nieewidencjonowane'],
            self::RATE_15 => ['69.10.Z — Legal services', '69.20.Z — Accounting/bookkeeping', '70.22.Z — Business consulting', '78.10.Z — Employment agencies', 'Immigration services'],
            self::RATE_14 => ['86.xx — Healthcare'],
            self::RATE_12 => ['62.01.Z — Software dev', '62.02.Z — IT consulting', '63.11.Z — Data processing'],
            self::RATE_10 => ['68.20.Z — Property rental'],
            self::RATE_8_5 => ['85.xx — Education', '74.xx — Design/architecture', 'Regulated liberal professions'],
            self::RATE_5_5 => ['41.xx — Construction', '10-33.xx — Manufacturing'],
            self::RATE_3 => ['47.xx — Retail trade', '56.xx — Gastronomy'],
            self::RATE_2 => ['01.xx — Agricultural products'],
        };
    }

    /**
     * Default ryczałt rate for WinCase immigration bureau.
     */
    public static function defaultForImmigration(): self
    {
        return self::RATE_15;
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum ставок рычалта (9 значений) по видам деятельности / PKD кодам.
// Для иммиграционного бюро WinCase: 15% (legal, consulting, immigration).
// PKD коды WinCase: 69.10.Z (юрид.), 70.22.Z (консалтинг), 78.10.Z (агентства).
// pkdExamples() — примеры PKD для каждой ставки.
// defaultForImmigration() → 15%.
// Файл: app/Enums/RyczaltRateEnum.php
// ---------------------------------------------------------------
