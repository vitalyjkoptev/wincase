<?php

namespace App\Enums;

enum PaymentMethodEnum: string
{
    case CASH = 'cash';
    case CARD = 'card';
    case CARD_TERMINAL = 'card_terminal';
    case TRANSFER = 'transfer';
    case BANK_TRANSFER = 'bank_transfer';
    case BLIK = 'blik';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Cash (Gotówka)',
            self::CARD => 'Card (Karta)',
            self::CARD_TERMINAL => 'Card Terminal (Terminal)',
            self::TRANSFER => 'Transfer (Przelew)',
            self::BANK_TRANSFER => 'Bank Transfer (Przelew)',
            self::BLIK => 'BLIK',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::CASH => 'banknote',
            self::CARD => 'credit-card',
            self::CARD_TERMINAL => 'credit-card',
            self::TRANSFER => 'building-2',
            self::BANK_TRANSFER => 'building-2',
            self::BLIK => 'smartphone',
        };
    }

    public function requiresTerminalId(): bool
    {
        return in_array($this, [
            self::CARD,
            self::CARD_TERMINAL,
            self::BLIK,
        ]);
    }

    public function isElectronic(): bool
    {
        return !in_array($this, [self::CASH]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum способов оплаты POS-терминала (4 значения).
// cash — наличные, card_terminal — банковский терминал,
// bank_transfer — перевод, blik — BLIK (популярен в Польше).
// requiresTerminalId() — нужен ли ID транзакции терминала.
// Файл: app/Enums/PaymentMethodEnum.php
// ---------------------------------------------------------------
