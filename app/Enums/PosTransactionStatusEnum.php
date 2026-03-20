<?php

namespace App\Enums;

enum PosTransactionStatusEnum: string
{
    case RECEIVED = 'received';
    case UNDER_REVIEW = 'under_review';
    case APPROVED = 'approved';
    case INVOICED = 'invoiced';
    case REJECTED = 'rejected';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::RECEIVED => 'Payment Received',
            self::UNDER_REVIEW => 'Under Review',
            self::APPROVED => 'Approved — Awaiting Invoice',
            self::INVOICED => 'Invoiced & Taxed',
            self::REJECTED => 'Rejected — Pending Refund',
            self::REFUNDED => 'Refunded to Client',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::RECEIVED => '#F59E0B',
            self::UNDER_REVIEW => '#8B5CF6',
            self::APPROVED => '#3B82F6',
            self::INVOICED => '#10B981',
            self::REJECTED => '#EF4444',
            self::REFUNDED => '#6B7280',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::RECEIVED => 'clock',
            self::UNDER_REVIEW => 'eye',
            self::APPROVED => 'check-circle',
            self::INVOICED => 'file-text',
            self::REJECTED => 'x-circle',
            self::REFUNDED => 'rotate-ccw',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [
            self::RECEIVED,
            self::UNDER_REVIEW,
        ]);
    }

    public function isFinalized(): bool
    {
        return in_array($this, [
            self::INVOICED,
            self::REFUNDED,
        ]);
    }

    public function canApprove(): bool
    {
        return in_array($this, [
            self::RECEIVED,
            self::UNDER_REVIEW,
        ]);
    }

    public function canReject(): bool
    {
        return in_array($this, [
            self::RECEIVED,
            self::UNDER_REVIEW,
        ]);
    }

    public function canRefund(): bool
    {
        return $this === self::REJECTED;
    }

    public function canInvoice(): bool
    {
        return $this === self::APPROVED;
    }

    /**
     * Allowed transitions from current status.
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::RECEIVED => [self::UNDER_REVIEW, self::APPROVED, self::REJECTED],
            self::UNDER_REVIEW => [self::APPROVED, self::REJECTED],
            self::APPROVED => [self::INVOICED],
            self::INVOICED => [],
            self::REJECTED => [self::REFUNDED],
            self::REFUNDED => [],
        };
    }

    public function funnelOrder(): int
    {
        return match ($this) {
            self::RECEIVED => 1,
            self::UNDER_REVIEW => 2,
            self::APPROVED => 3,
            self::INVOICED => 4,
            self::REJECTED => 90,
            self::REFUNDED => 91,
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum статусов POS-транзакции — staging-зона платежей.
// Поток:
//   received → under_review → approved → invoiced (УСПЕХ: счёт + налог)
//   received → under_review → rejected → refunded (ОТКАЗ: возврат средств)
// Методы:
//   canApprove/canReject/canRefund/canInvoice — проверка допустимых действий.
//   allowedTransitions() — граф допустимых переходов статусов.
//   isActive() — ожидает решения владельца.
//   isFinalized() — финальное состояние (invoiced или refunded).
// Файл: app/Enums/PosTransactionStatusEnum.php
// ---------------------------------------------------------------
