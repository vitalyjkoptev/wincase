<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add online payment gateway fields to payments table.
 * Supports: Stripe, Przelewy24, PayPal.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Gateway identification
            $table->string('gateway', 30)->nullable()->after('payment_method');        // stripe, przelewy24, paypal
            $table->string('gateway_payment_id', 255)->nullable()->after('gateway');   // pi_xxx, session_xxx, ORDER-xxx
            $table->string('gateway_status', 50)->nullable()->after('gateway_payment_id'); // gateway-specific status
            $table->string('checkout_session_id', 255)->nullable()->after('gateway_status'); // Stripe checkout session

            // Missing fields from model (may already exist — use nullable + change)
            if (!Schema::hasColumn('payments', 'pos_transaction_id')) {
                $table->unsignedBigInteger('pos_transaction_id')->nullable()->after('case_id');
            }
            if (!Schema::hasColumn('payments', 'received_by')) {
                $table->unsignedBigInteger('received_by')->nullable()->after('status');
            }

            // Gateway metadata (refund info, failure reason, etc.)
            $table->json('gateway_metadata')->nullable()->after('checkout_session_id');
            $table->string('failure_reason', 500)->nullable()->after('gateway_metadata');
            $table->decimal('refund_amount', 12, 2)->nullable()->after('failure_reason');
            $table->timestamp('refunded_at')->nullable()->after('refund_amount');

            // Indexes
            $table->index('gateway');
            $table->index('gateway_payment_id');
            $table->index('checkout_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['gateway']);
            $table->dropIndex(['gateway_payment_id']);
            $table->dropIndex(['checkout_session_id']);

            $table->dropColumn([
                'gateway', 'gateway_payment_id', 'gateway_status',
                'checkout_session_id', 'gateway_metadata', 'failure_reason',
                'refund_amount', 'refunded_at',
            ]);
        });
    }
};
