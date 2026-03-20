<?php

namespace App\Enums;

enum LeadStatusEnum: string
{
    case NEW = 'new';
    case CONTACTED = 'contacted';
    case QUALIFIED = 'qualified';
    case CONSULTATION = 'consultation';
    case PROPOSAL = 'proposal';
    case NEGOTIATION = 'negotiation';
    case CONTRACT = 'contract';
    case WON = 'won';
    case PAID = 'paid';
    case LOST = 'lost';
    case REJECTED = 'rejected';
    case SPAM = 'spam';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'New Lead',
            self::CONTACTED => 'Contacted',
            self::QUALIFIED => 'Qualified',
            self::CONSULTATION => 'Consultation',
            self::PROPOSAL => 'Proposal Sent',
            self::NEGOTIATION => 'Negotiation',
            self::CONTRACT => 'Contract Signed',
            self::WON => 'Won',
            self::PAID => 'Payment Received',
            self::LOST => 'Lost',
            self::REJECTED => 'Rejected',
            self::SPAM => 'Spam',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NEW => '#3B82F6',
            self::CONTACTED => '#F59E0B',
            self::QUALIFIED => '#8B5CF6',
            self::CONSULTATION => '#6366F1',
            self::PROPOSAL => '#EC4899',
            self::NEGOTIATION => '#F97316',
            self::CONTRACT => '#10B981',
            self::WON => '#059669',
            self::PAID => '#047857',
            self::LOST => '#6B7280',
            self::REJECTED => '#EF4444',
            self::SPAM => '#9CA3AF',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [
            self::NEW,
            self::CONTACTED,
            self::QUALIFIED,
            self::CONSULTATION,
            self::PROPOSAL,
            self::NEGOTIATION,
            self::CONTRACT,
        ]);
    }

    public function isClosed(): bool
    {
        return in_array($this, [
            self::WON,
            self::PAID,
            self::LOST,
            self::REJECTED,
            self::SPAM,
        ]);
    }

    public function funnelOrder(): int
    {
        return match ($this) {
            self::NEW => 1,
            self::CONTACTED => 2,
            self::QUALIFIED => 3,
            self::CONSULTATION => 4,
            self::PROPOSAL => 5,
            self::NEGOTIATION => 6,
            self::CONTRACT => 7,
            self::WON => 8,
            self::PAID => 9,
            self::LOST => 99,
            self::REJECTED => 100,
            self::SPAM => 101,
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum статусов лида в воронке продаж (7 значений).
// Активные: new → contacted → consultation → contract.
// Закрытые: paid (успешно), rejected (отказ), spam.
// Методы: label(), color() — цвет для UI, isActive(), isClosed(),
// funnelOrder() — порядок в воронке для сортировки и визуализации.
// Файл: app/Enums/LeadStatusEnum.php
// ---------------------------------------------------------------
