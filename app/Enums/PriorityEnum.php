<?php

namespace App\Enums;

// =====================================================
// FILE: app/Enums/PriorityEnum.php
// =====================================================

enum PriorityEnum: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::URGENT => 'Urgent',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::LOW => '#6B7280',
            self::MEDIUM => '#3B82F6',
            self::HIGH => '#F59E0B',
            self::URGENT => '#EF4444',
        };
    }

    public function sortOrder(): int
    {
        return match ($this) {
            self::URGENT => 1,
            self::HIGH => 2,
            self::MEDIUM => 3,
            self::LOW => 4,
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum приоритетов (4 значения). sortOrder() — для сортировки (urgent первый).
// Файл: app/Enums/PriorityEnum.php
// ---------------------------------------------------------------
