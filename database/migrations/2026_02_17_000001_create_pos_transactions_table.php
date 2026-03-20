<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_transactions', function (Blueprint $table) {
            $table->id();

            // --- Receipt / Transaction ID ---
            $table->string('receipt_number', 30)->unique();
            $table->string('terminal_transaction_id', 100)->nullable();

            // --- Client Info (may NOT be a CRM client yet) ---
            $table->string('client_name', 200);
            $table->string('client_phone', 30);
            $table->string('client_email', 100)->nullable();
            $table->string('client_passport', 50)->nullable();

            // --- Service & Documents ---
            $table->string('service_type', 30);
            $table->text('service_description')->nullable();
            $table->json('documents_submitted')->nullable();

            // --- Payment ---
            $table->string('payment_method', 20);
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('PLN');
            $table->decimal('tax_rate', 5, 2)->default(23.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('net_amount', 10, 2)->default(0.00);

            // --- Status & Decision Flow ---
            $table->string('status', 20)->default('received');
            $table->text('decision_notes')->nullable();
            $table->foreignId('decided_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();

            // --- Refund (if rejected) ---
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->string('refund_method', 20)->nullable();
            $table->text('refund_reason')->nullable();
            $table->timestamp('refunded_at')->nullable();

            // --- CRM Link (only after approval) ---
            $table->foreignId('client_id')->nullable()
                  ->constrained('clients')->nullOnDelete();
            $table->foreignId('case_id')->nullable()
                  ->constrained('cases')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()
                  ->constrained('invoices')->nullOnDelete();
            $table->foreignId('payment_id')->nullable()
                  ->constrained('payments')->nullOnDelete();

            // --- Operator who accepted payment ---
            $table->foreignId('received_by')->nullable()
                  ->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // --- Indexes ---
            $table->index('status');
            $table->index('payment_method');
            $table->index('client_phone');
            $table->index('created_at');
            $table->index('decided_at');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_transactions');
    }
};

// ---------------------------------------------------------------
// Аннотация (RU):
// Миграция таблицы pos_transactions — STAGING-зона платежей.
// Это ОТДЕЛЬНАЯ таблица от основных invoices/payments CRM.
//
// Бизнес-логика:
// 1. Клиент приходит в офис → заполняет документы → оператор вводит данные
// 2. Клиент оплачивает (наличные / терминал / BLIK / перевод)
// 3. Генерируется чек (receipt_number)
// 4. Транзакция ждёт решения владельца в staging-таблице
// 5. Владелец APPROVED → создаётся invoice + payment в CRM, считается налог
// 6. Владелец REJECTED → refund клиенту, данные остаются для отчётности
//
// CRM-связи (client_id, case_id, invoice_id, payment_id) заполняются
// ТОЛЬКО после approved → invoiced. До этого — NULL.
//
// Налог: tax_rate 23% (VAT Польша), tax_amount и net_amount
// рассчитываются при утверждении (invoiced).
//
// 6 индексов для фильтрации по статусу, методу оплаты, телефону, датам.
// Файл: database/migrations/2026_02_17_000001_create_pos_transactions_table.php
// ---------------------------------------------------------------
