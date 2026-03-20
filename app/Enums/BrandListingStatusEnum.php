<?php

namespace App\Enums;

enum BrandListingStatusEnum: string
{
    case LISTED = 'listed';
    case PENDING = 'pending';
    case NOT_LISTED = 'not_listed';
    case ERROR = 'error';

    public function label(): string
    {
        return match ($this) {
            self::LISTED => 'Listed',
            self::PENDING => 'Pending',
            self::NOT_LISTED => 'Not Listed',
            self::ERROR => 'Error',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::LISTED => '#10B981',
            self::PENDING => '#F59E0B',
            self::NOT_LISTED => '#6B7280',
            self::ERROR => '#EF4444',
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum статусов каталога бренда (4 значения) — для NAP consistency check.
// Файл: app/Enums/BrandListingStatusEnum.php
// ---------------------------------------------------------------
