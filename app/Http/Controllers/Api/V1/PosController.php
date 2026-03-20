<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PosTransaction;
use App\Services\PosService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function __construct(
        protected PosService $posService
    ) {}

    // =====================================================
    // 0. GET /api/v1/pos — All transactions + stats
    // =====================================================

    public function index(Request $request): JsonResponse
    {
        $query = PosTransaction::with(['receivedByUser:id,name', 'decidedByUser:id,name']);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->get('payment_method'));
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->get('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->get('to'));
        }
        if ($request->filled('search')) {
            $s = $request->get('search');
            $query->where(function ($q) use ($s) {
                $q->where('client_name', 'like', "%{$s}%")
                  ->orWhere('client_phone', 'like', "%{$s}%")
                  ->orWhere('receipt_number', 'like', "%{$s}%");
            });
        }

        $transactions = $query->orderByDesc('created_at')->get();

        // Stats — use DB queries (collection where with dates fails on server)
        $totalReceived = (float) PosTransaction::where('status', 'approved')->sum('amount');
        $todayTotal = (float) PosTransaction::whereDate('created_at', today())->sum('amount');
        $todayCount = PosTransaction::whereDate('created_at', today())->count();
        $pendingCount = PosTransaction::where('status', 'pending')->count();
        $pendingAmount = (float) PosTransaction::where('status', 'pending')->sum('amount');
        $refundedAmount = (float) PosTransaction::where('status', 'refunded')->sum('refund_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'transactions' => $transactions,
                'stats' => [
                    'total_received' => $totalReceived,
                    'today_total' => $todayTotal,
                    'today_count' => $todayCount,
                    'pending_count' => $pendingCount,
                    'pending_amount' => $pendingAmount,
                    'refunded_amount' => $refundedAmount,
                ],
            ],
        ]);
    }

    // =====================================================
    // 0b. GET /api/v1/pos/statistics — Summary stats
    // =====================================================

    public function statistics(Request $request): JsonResponse
    {
        $monthStart = now()->startOfMonth()->toDateString();

        return response()->json([
            'success' => true,
            'data' => [
                'today' => [
                    'count' => PosTransaction::whereDate('created_at', today())->count(),
                    'amount' => (float) PosTransaction::whereDate('created_at', today())->sum('amount'),
                ],
                'this_month' => [
                    'count' => PosTransaction::whereDate('created_at', '>=', $monthStart)->count(),
                    'amount' => (float) PosTransaction::whereDate('created_at', '>=', $monthStart)->sum('amount'),
                ],
                'pending_count' => PosTransaction::where('status', 'pending')->count(),
                'approved_count' => PosTransaction::where('status', 'approved')->count(),
                'refunded_count' => PosTransaction::where('status', 'refunded')->count(),
            ],
        ]);
    }

    // =====================================================
    // 0c. GET /api/v1/pos/receipt/{id} — Receipt data
    // =====================================================

    public function receipt(int $id): JsonResponse
    {
        $txn = PosTransaction::with(['receivedByUser:id,name', 'client'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $txn,
        ]);
    }

    // =====================================================
    // 0d. GET /api/v1/pos/report/monthly — Monthly report
    // =====================================================

    public function monthlyReport(Request $request): JsonResponse
    {
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);

        $txns = PosTransaction::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'year' => $year,
                'month' => $month,
                'total_count' => $txns->count(),
                'total_amount' => (float) $txns->sum('amount'),
                'by_method' => $txns->groupBy('payment_method')->map(fn ($g) => [
                    'count' => $g->count(),
                    'amount' => (float) $g->sum('amount'),
                ]),
                'by_status' => $txns->groupBy('status')->map(fn ($g) => [
                    'count' => $g->count(),
                    'amount' => (float) $g->sum('amount'),
                ]),
                'approved_amount' => (float) $txns->where('status', 'approved')->sum('amount'),
                'refunded_amount' => (float) $txns->where('status', 'refunded')->sum('refund_amount'),
            ],
        ]);
    }

    // =====================================================
    // 1. POST /api/v1/pos/receive — Operator registers payment
    // =====================================================

    public function receive(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:200',
            'client_phone' => 'required|string|max:30',
            'client_email' => 'nullable|email|max:100',
            'client_passport' => 'nullable|string|max:50',
            'service_type' => 'required|string|max:30',
            'service_description' => 'nullable|string|max:1000',
            'documents_submitted' => 'nullable|array',
            'documents_submitted.*' => 'string|max:200',
            'payment_method' => 'required|string|in:cash,card_terminal,bank_transfer,blik',
            'amount' => 'required|numeric|min:1|max:99999.99',
            'currency' => 'nullable|string|in:PLN,EUR,USD',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'terminal_transaction_id' => 'nullable|string|max:100',
        ]);

        $transaction = $this->posService->receivePayment(
            $validated,
            $request->user()->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment received. Receipt: ' . $transaction->receipt_number,
            'data' => $transaction,
        ], 201);
    }

    // =====================================================
    // 2. GET /api/v1/pos/pending — Owner: list pending
    // =====================================================

    public function pending(): JsonResponse
    {
        $transactions = $this->posService->getPendingTransactions();

        return response()->json([
            'success' => true,
            'data' => $transactions,
            'stats' => $this->posService->getPendingStats(),
        ]);
    }

    // =====================================================
    // 3. GET /api/v1/pos/{id} — View single transaction
    // =====================================================

    public function show(PosTransaction $posTransaction): JsonResponse
    {
        $posTransaction->load([
            'decidedByUser',
            'receivedByUser',
            'client',
            'invoice',
            'payment',
        ]);

        return response()->json([
            'success' => true,
            'data' => $posTransaction,
        ]);
    }

    // =====================================================
    // 4. PATCH /api/v1/pos/{id}/review — Owner marks for review
    // =====================================================

    public function markForReview(PosTransaction $posTransaction): JsonResponse
    {
        $posTransaction->markForReview();

        return response()->json([
            'success' => true,
            'message' => "Transaction #{$posTransaction->receipt_number} marked for review.",
            'data' => $posTransaction->fresh(),
        ]);
    }

    // =====================================================
    // 5. PATCH /api/v1/pos/{id}/approve — Owner approves
    // =====================================================

    public function approve(Request $request, PosTransaction $posTransaction): JsonResponse
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $transaction = $this->posService->approveTransaction(
            $posTransaction,
            $request->user()->id,
            $validated['notes'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => "Approved. VAT {$transaction->tax_amount} {$transaction->currency}. Ready for invoice.",
            'data' => $transaction,
        ]);
    }

    // =====================================================
    // 6. PATCH /api/v1/pos/{id}/reject — Owner rejects
    // =====================================================

    public function reject(Request $request, PosTransaction $posTransaction): JsonResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $transaction = $this->posService->rejectTransaction(
            $posTransaction,
            $request->user()->id,
            $validated['reason']
        );

        return response()->json([
            'success' => true,
            'message' => "Rejected. Refund pending: {$transaction->refund_amount} {$transaction->currency}.",
            'data' => $transaction,
        ]);
    }

    // =====================================================
    // 7. POST /api/v1/pos/{id}/process — Create CRM records
    // =====================================================

    public function process(Request $request, PosTransaction $posTransaction): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => 'nullable|integer|exists:clients,id',
            'case_id' => 'nullable|integer|exists:cases,id',
        ]);

        $transaction = $this->posService->processApprovedTransaction(
            $posTransaction,
            $validated['client_id'] ?? null,
            $validated['case_id'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => "Invoice created. CRM updated. Receipt: {$transaction->receipt_number}.",
            'data' => $transaction->load(['client', 'invoice', 'payment']),
        ]);
    }

    // =====================================================
    // 8. PATCH /api/v1/pos/{id}/refund — Process refund
    // =====================================================

    public function refund(Request $request, PosTransaction $posTransaction): JsonResponse
    {
        $validated = $request->validate([
            'refund_method' => 'nullable|string|in:cash,card_terminal,bank_transfer,blik',
        ]);

        $transaction = $this->posService->processRefund(
            $posTransaction,
            $validated['refund_method'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => "Refunded: {$transaction->refund_amount} {$transaction->currency}.",
            'data' => $transaction,
        ]);
    }

    // =====================================================
    // 9. GET /api/v1/pos/daily-report — Daily summary
    // =====================================================

    public function dailyReport(Request $request): JsonResponse
    {
        $date = $request->get('date', today()->toDateString());

        return response()->json([
            'success' => true,
            'data' => PosTransaction::dailySummary($date),
        ]);
    }

    // =====================================================
    // 10. GET /api/v1/pos/tax-report — Monthly tax report
    // =====================================================

    public function taxReport(Request $request): JsonResponse
    {
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);

        return response()->json([
            'success' => true,
            'data' => PosTransaction::monthlyTaxReport($year, $month),
        ]);
    }

    // =====================================================
    // 11. GET /api/v1/pos/history — All transactions with filters
    // =====================================================

    public function history(Request $request): JsonResponse
    {
        $query = PosTransaction::with(['decidedByUser', 'receivedByUser']);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->get('payment_method'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->get('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->get('to'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_phone', 'like', "%{$search}%")
                  ->orWhere('receipt_number', 'like', "%{$search}%");
            });
        }

        $transactions = $query->orderByDesc('created_at')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Контроллер PosController — 11 API endpoints POS-модуля.
//
// ENDPOINTS:
//   POST   /api/v1/pos/receive         — Оператор регистрирует оплату
//   GET    /api/v1/pos/pending         — Владелец: список ожидающих решения
//   GET    /api/v1/pos/{id}            — Детали транзакции
//   PATCH  /api/v1/pos/{id}/review     — Пометить "на рассмотрении"
//   PATCH  /api/v1/pos/{id}/approve    — Утвердить (считается VAT)
//   PATCH  /api/v1/pos/{id}/reject     — Отклонить (pending refund)
//   POST   /api/v1/pos/{id}/process    — Создать Invoice+Payment в CRM
//   PATCH  /api/v1/pos/{id}/refund     — Подтвердить возврат
//   GET    /api/v1/pos/daily-report    — Дневной отчёт
//   GET    /api/v1/pos/tax-report      — Месячный налоговый отчёт (VAT)
//   GET    /api/v1/pos/history         — История с фильтрами и поиском
//
// Валидация: все входные данные валидируются inline.
// Авторизация: $request->user()->id для decided_by / received_by.
// Файл: app/Http/Controllers/Api/V1/PosController.php
// ---------------------------------------------------------------
