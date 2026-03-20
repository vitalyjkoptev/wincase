<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TaxRegimeEnum;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TaxReport;
use App\Services\Accounting\TaxCalculatorService;
use App\Services\Accounting\TaxReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function __construct(
        protected TaxCalculatorService $calculator,
        protected TaxReportService $reportService
    ) {}

    // =====================================================
    // INVOICES — CRUD
    // =====================================================

    /**
     * GET /api/v1/accounting/invoices
     * List invoices with filters + stats.
     */
    public function invoices(Request $request): JsonResponse
    {
        $query = Invoice::with(['client:id,first_name,last_name', 'case:id,case_number', 'createdBy:id,name']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('from')) {
            $query->whereDate('issue_date', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('issue_date', '<=', $request->input('to'));
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->input('client_id'));
        }
        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('invoice_number', 'like', "%{$s}%")
                  ->orWhereHas('client', function ($cq) use ($s) {
                      $cq->where('first_name', 'like', "%{$s}%")
                         ->orWhere('last_name', 'like', "%{$s}%");
                  });
            });
        }

        $invoices = $query->orderByDesc('issue_date')->orderByDesc('id')->get();

        // Stats
        $all = Invoice::all();
        $totalIssued = (float) $all->sum('total_amount');
        $totalPaid = (float) $all->where('status', 'paid')->sum('total_amount');
        $overdue = $all->where('status', '!=', 'paid')->where('due_date', '<', now()->toDateString());
        $overdueAmount = (float) $overdue->sum('total_amount');
        $overdueCount = $overdue->count();
        $pending = $all->whereIn('status', ['sent', 'draft']);
        $pendingAmount = (float) $pending->sum('total_amount');
        $pendingCount = $pending->count();

        return response()->json([
            'success' => true,
            'data' => [
                'invoices' => $invoices,
                'stats' => [
                    'total_issued' => $totalIssued,
                    'total_paid' => $totalPaid,
                    'overdue_amount' => $overdueAmount,
                    'overdue_count' => $overdueCount,
                    'pending_amount' => $pendingAmount,
                    'pending_count' => $pendingCount,
                ],
            ],
        ]);
    }

    /**
     * POST /api/v1/accounting/invoices
     */
    public function createInvoice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'case_id' => 'nullable|integer',
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'net_amount' => 'required|numeric|min:0.01',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        $netAmount = (float) $validated['net_amount'];
        $vatRate = (float) ($validated['vat_rate'] ?? 23);
        $vatAmount = round($netAmount * $vatRate / 100, 2);
        $grossAmount = $netAmount + $vatAmount;

        $invoice = Invoice::create([
            'client_id' => $validated['client_id'],
            'case_id' => $validated['case_id'] ?? null,
            'issue_date' => $validated['issue_date'] ?? now()->toDateString(),
            'due_date' => $validated['due_date'] ?? now()->addDays(14)->toDateString(),
            'net_amount' => $netAmount,
            'vat_rate' => $vatRate,
            'vat_amount' => $vatAmount,
            'gross_amount' => $grossAmount,
            'total_amount' => $grossAmount,
            'currency' => 'PLN',
            'status' => 'draft',
            'payment_method' => $validated['payment_method'] ?? 'bank_transfer',
            'notes' => $validated['notes'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        $invoice->load(['client:id,first_name,last_name', 'case:id,case_number']);

        return response()->json([
            'success' => true,
            'message' => 'Invoice created: ' . $invoice->invoice_number,
            'data' => $invoice,
        ], 201);
    }

    /**
     * GET /api/v1/accounting/invoices/{id}
     */
    public function showInvoice(int $id): JsonResponse
    {
        $invoice = Invoice::with(['client', 'case', 'createdBy:id,name', 'payments'])
            ->findOrFail($id);

        $totalPaid = (float) $invoice->payments->where('status', 'completed')->sum('amount');

        return response()->json([
            'success' => true,
            'data' => $invoice,
            'total_paid' => $totalPaid,
            'remaining' => max(0, (float) $invoice->total_amount - $totalPaid),
        ]);
    }

    /**
     * PUT /api/v1/accounting/invoices/{id}
     */
    public function updateInvoice(Request $request, int $id): JsonResponse
    {
        $invoice = Invoice::findOrFail($id);

        $validated = $request->validate([
            'status' => 'nullable|string|in:draft,sent,paid,overdue,cancelled',
            'due_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'net_amount' => 'nullable|numeric|min:0.01',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        // Recalculate if amounts changed
        if (isset($validated['net_amount']) || isset($validated['vat_rate'])) {
            $net = (float) ($validated['net_amount'] ?? $invoice->net_amount);
            $vat = (float) ($validated['vat_rate'] ?? $invoice->vat_rate);
            $validated['net_amount'] = $net;
            $validated['vat_rate'] = $vat;
            $validated['vat_amount'] = round($net * $vat / 100, 2);
            $validated['gross_amount'] = $net + $validated['vat_amount'];
            $validated['total_amount'] = $validated['gross_amount'];
        }

        if (isset($validated['status']) && $validated['status'] === 'paid') {
            $validated['paid_date'] = now()->toDateString();
        }

        $invoice->update(array_filter($validated, fn ($v) => $v !== null));
        $invoice->load(['client:id,first_name,last_name', 'case:id,case_number']);

        return response()->json([
            'success' => true,
            'message' => 'Invoice updated',
            'data' => $invoice,
        ]);
    }

    /**
     * GET /api/v1/accounting/invoices/{id}/pdf
     */
    public function invoicePdf(int $id): JsonResponse
    {
        $invoice = Invoice::with(['client', 'case'])->findOrFail($id);

        // Return invoice data for client-side PDF generation
        return response()->json([
            'success' => true,
            'data' => $invoice,
        ]);
    }

    // =====================================================
    // REPORTS — Generation
    // =====================================================

    /**
     * POST /api/v1/accounting/reports/monthly
     * Generate full monthly tax report.
     */
    public function generateMonthlyReport(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2024|max:2030',
            'month' => 'required|integer|min:1|max:12',
            'tax_regime' => 'required|string',
            'ryczalt_rate' => 'nullable|numeric|min:2|max:17',
            'zus_stage' => 'nullable|string|in:ulga_na_start,preferential,full',
        ]);

        $regime = TaxRegimeEnum::from($validated['tax_regime']);

        $report = $this->reportService->generateMonthlyTaxReport(
            year: $validated['year'],
            month: $validated['month'],
            regime: $regime,
            userId: $request->user()->id,
            ryczaltRate: $validated['ryczalt_rate'] ?? 15.0,
            zusStage: $validated['zus_stage'] ?? 'full'
        );

        return response()->json([
            'success' => true,
            'message' => "Monthly tax report generated for {$validated['year']}/{$validated['month']}.",
            'data' => $report,
        ], 201);
    }

    /**
     * POST /api/v1/accounting/reports/vat
     * Generate VAT (JPK_VAT) report.
     */
    public function generateVatReport(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $report = $this->reportService->generateVatReport(
            $validated['year'],
            $validated['month'],
            $request->user()->id
        );

        return response()->json([
            'success' => true,
            'message' => "JPK_VAT report generated for {$validated['year']}/{$validated['month']}.",
            'data' => $report,
        ], 201);
    }

    /**
     * POST /api/v1/accounting/reports/zus
     * Generate ZUS DRA report.
     */
    public function generateZusReport(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'tax_regime' => 'required|string',
            'zus_stage' => 'nullable|string|in:ulga_na_start,preferential,full',
        ]);

        $regime = TaxRegimeEnum::from($validated['tax_regime']);

        $report = $this->reportService->generateZusReport(
            $validated['year'],
            $validated['month'],
            $regime,
            $request->user()->id,
            $validated['zus_stage'] ?? 'full'
        );

        return response()->json([
            'success' => true,
            'message' => "ZUS DRA report generated for {$validated['year']}/{$validated['month']}.",
            'data' => $report,
        ], 201);
    }

    /**
     * POST /api/v1/accounting/reports/annual
     * Generate annual tax declaration.
     */
    public function generateAnnualReport(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer',
            'tax_regime' => 'required|string',
            'ryczalt_rate' => 'nullable|numeric|min:2|max:17',
        ]);

        $regime = TaxRegimeEnum::from($validated['tax_regime']);

        $report = $this->reportService->generateAnnualReport(
            $validated['year'],
            $regime,
            $request->user()->id,
            $validated['ryczalt_rate'] ?? 15.0
        );

        return response()->json([
            'success' => true,
            'message' => "Annual declaration generated for {$validated['year']} ({$regime->pitForm()}).",
            'data' => $report,
        ], 201);
    }

    /**
     * POST /api/v1/accounting/reports/profit-loss
     * Generate P&L report.
     */
    public function generateProfitLoss(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $report = $this->reportService->generateProfitLoss(
            $validated['year'],
            $validated['month'],
            $request->user()->id
        );

        return response()->json([
            'success' => true,
            'data' => $report,
        ], 201);
    }

    // =====================================================
    // REPORTS — View & Manage
    // =====================================================

    /**
     * GET /api/v1/accounting/reports
     * List all reports with filters.
     */
    public function listReports(Request $request): JsonResponse
    {
        $query = TaxReport::orderByDesc('generated_at');

        if ($request->filled('type')) {
            $query->byType($request->get('type'));
        }
        if ($request->filled('year')) {
            $query->forYear((int) $request->get('year'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(20),
            'report_types' => TaxReport::reportTypes(),
        ]);
    }

    /**
     * GET /api/v1/accounting/reports/{id}
     * View single report with full data.
     */
    public function showReport(TaxReport $report): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }

    /**
     * PATCH /api/v1/accounting/reports/{id}/submit
     * Mark report as submitted to tax office (manual).
     */
    public function markSubmitted(Request $request, TaxReport $report): JsonResponse
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $report->markAsSubmitted($validated['notes'] ?? null);

        return response()->json([
            'success' => true,
            'message' => "Report marked as submitted to tax office.",
            'data' => $report->fresh(),
        ]);
    }

    // =====================================================
    // TAX CALCULATOR — Quick calculations
    // =====================================================

    /**
     * POST /api/v1/accounting/calculate
     * Quick tax calculation (without saving).
     */
    public function calculate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tax_regime' => 'required|string',
            'monthly_gross_revenue' => 'required|numeric|min:0',
            'monthly_costs' => 'nullable|numeric|min:0',
            'vat_output' => 'nullable|numeric|min:0',
            'vat_input' => 'nullable|numeric|min:0',
            'cumulative_income' => 'nullable|numeric|min:0',
            'previous_advances' => 'nullable|numeric|min:0',
            'ryczalt_rate' => 'nullable|numeric|min:2|max:17',
            'zus_stage' => 'nullable|string|in:ulga_na_start,preferential,full',
        ]);

        $regime = TaxRegimeEnum::from($validated['tax_regime']);

        $result = $this->calculator->calculateMonthlyTotal(
            regime: $regime,
            monthlyGrossRevenue: $validated['monthly_gross_revenue'],
            monthlyCosts: $validated['monthly_costs'] ?? 0,
            vatOutput: $validated['vat_output'] ?? 0,
            vatInput: $validated['vat_input'] ?? 0,
            cumulativeIncome: $validated['cumulative_income'] ?? 0,
            previousPitAdvances: $validated['previous_advances'] ?? 0,
            zusStage: $validated['zus_stage'] ?? 'full',
            ryczaltRate: $validated['ryczalt_rate'] ?? 15.0
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * POST /api/v1/accounting/compare-regimes
     * Compare tax regimes (which is cheaper).
     */
    public function compareRegimes(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'annual_revenue' => 'required|numeric|min:0',
            'annual_costs' => 'nullable|numeric|min:0',
            'zus_stage' => 'nullable|string|in:ulga_na_start,preferential,full',
        ]);

        $result = $this->calculator->compareRegimes(
            $validated['annual_revenue'],
            $validated['annual_costs'] ?? 0,
            $validated['zus_stage'] ?? 'full'
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    // =====================================================
    // EXPENSES — CRUD
    // =====================================================

    /**
     * GET /api/v1/accounting/expenses
     */
    public function listExpenses(Request $request): JsonResponse
    {
        $query = Expense::orderByDesc('date');

        if ($request->filled('year') && $request->filled('month')) {
            $query->forMonth((int) $request->get('year'), (int) $request->get('month'));
        } elseif ($request->filled('year')) {
            $query->forYear((int) $request->get('year'));
        }

        if ($request->filled('category')) {
            $query->byCategory($request->get('category'));
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(30),
            'categories' => Expense::CATEGORIES,
        ]);
    }

    /**
     * POST /api/v1/accounting/expenses
     */
    public function storeExpense(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:50',
            'description' => 'required|string|max:500',
            'vendor' => 'nullable|string|max:200',
            'vendor_nip' => 'nullable|string|max:15',
            'invoice_number' => 'nullable|string|max:100',
            'net_amount' => 'required|numeric|min:0.01',
            'vat_rate' => 'required|string|in:23,8,5,0,zw,np',
            'gross_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string|max:20',
            'is_tax_deductible' => 'nullable|boolean',
            'deductible_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $validated['vat_amount'] = round($validated['gross_amount'] - $validated['net_amount'], 2);
        $validated['is_tax_deductible'] = $validated['is_tax_deductible'] ?? true;
        $validated['deductible_percentage'] = $validated['deductible_percentage'] ?? 100.00;

        $expense = Expense::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Expense recorded.',
            'data' => $expense,
        ], 201);
    }

    /**
     * DELETE /api/v1/accounting/expenses/{id}
     */
    public function destroyExpense(Expense $expense): JsonResponse
    {
        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expense deleted.',
        ]);
    }

    // =====================================================
    // PERIODS — View & Close
    // =====================================================

    /**
     * GET /api/v1/accounting/periods
     */
    public function listPeriods(Request $request): JsonResponse
    {
        $year = (int) $request->get('year', now()->year);

        $periods = AccountingPeriod::forYear($year)
            ->orderBy('month')
            ->get();

        $cumulative = AccountingPeriod::cumulativeForYear($year);

        return response()->json([
            'success' => true,
            'data' => [
                'periods' => $periods,
                'cumulative' => $cumulative,
            ],
        ]);
    }

    /**
     * PATCH /api/v1/accounting/periods/{id}/close
     */
    public function closePeriod(Request $request, AccountingPeriod $period): JsonResponse
    {
        $period->close($request->user()->id);

        return response()->json([
            'success' => true,
            'message' => "Period {$period->periodLabel} closed.",
            'data' => $period->fresh(),
        ]);
    }

    // =====================================================
    // DASHBOARD — Tax config & rates
    // =====================================================

    /**
     * GET /api/v1/accounting/tax-config
     * Get all tax rates and config for UI.
     */
    public function taxConfig(): JsonResponse
    {
        $config = config('polish_tax');

        return response()->json([
            'success' => true,
            'data' => [
                'tax_year' => $config['tax_year'],
                'company' => $config['company'],
                'regimes' => collect(TaxRegimeEnum::cases())->map(fn ($r) => [
                    'value' => $r->value,
                    'label' => $r->label(),
                    'label_pl' => $r->labelPl(),
                    'is_jdg' => $r->isJdg(),
                    'pit_form' => $r->pitForm(),
                    'allows_cost_deduction' => $r->allowsCostDeduction(),
                    'tax_free_allowance' => $r->taxFreeAllowance(),
                ]),
                'vat_rates' => $config['vat'],
                'zus' => $config['zus'],
                'ryczalt_rates' => $config['pit']['ryczalt']['rates'],
                'deadlines' => $config['deadlines'],
                'expense_categories' => Expense::CATEGORIES,
                'report_types' => TaxReport::reportTypes(),
            ],
        ]);
    }

    // =====================================================
    // TAX OVERVIEW ENDPOINTS
    // =====================================================

    public function pit(Request $request): JsonResponse
    {
        $year = (int) $request->get('year', now()->year);
        $reports = TaxReport::where('report_type', 'pit')
            ->whereYear('period_start', $year)
            ->orderBy('period_start')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'year' => $year,
                'reports' => $reports,
                'total_tax' => $reports->sum('tax_amount'),
            ],
        ]);
    }

    public function cit(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'CIT not applicable — company operates as JDG (sole proprietor).',
                'applicable' => false,
            ],
        ]);
    }

    public function vat(Request $request): JsonResponse
    {
        $year = (int) $request->get('year', now()->year);
        $reports = TaxReport::where('report_type', 'vat')
            ->whereYear('period_start', $year)
            ->orderBy('period_start')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'year' => $year,
                'reports' => $reports,
                'total_vat' => $reports->sum('tax_amount'),
            ],
        ]);
    }

    public function zus(Request $request): JsonResponse
    {
        $year = (int) $request->get('year', now()->year);
        $reports = TaxReport::where('report_type', 'zus')
            ->whereYear('period_start', $year)
            ->orderBy('period_start')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'year' => $year,
                'reports' => $reports,
                'total_zus' => $reports->sum('tax_amount'),
            ],
        ]);
    }

    public function taxCalendar(Request $request): JsonResponse
    {
        $config = config('polish_tax');
        $deadlines = $config['deadlines'] ?? [];
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);

        return response()->json([
            'success' => true,
            'data' => [
                'year' => $year,
                'month' => $month,
                'deadlines' => $deadlines,
            ],
        ]);
    }

    // =====================================================
    // FINANCIAL REPORTS
    // =====================================================

    public function pnl(Request $request): JsonResponse
    {
        $year = (int) $request->get('year', now()->year);
        $invoices = Invoice::whereYear('issue_date', $year)->get();
        $expenses = Expense::whereYear('date', $year)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'year' => $year,
                'total_revenue' => $invoices->sum('total_amount'),
                'total_expenses' => $expenses->sum('gross_amount'),
                'profit' => $invoices->sum('total_amount') - $expenses->sum('gross_amount'),
                'months' => collect(range(1, 12))->map(fn ($m) => [
                    'month' => $m,
                    'revenue' => $invoices->filter(fn ($i) => $i->issue_date->month === $m)->sum('total_amount'),
                    'expenses' => $expenses->filter(fn ($e) => $e->date->month === $m)->sum('gross_amount'),
                ]),
            ],
        ]);
    }

    public function balance(Request $request): JsonResponse
    {
        $payments = Payment::sum('amount');
        $expenses = Expense::sum('gross_amount');
        $invoicesPending = Invoice::where('status', 'sent')->sum('total_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'total_received' => $payments,
                'total_expenses' => $expenses,
                'balance' => $payments - $expenses,
                'pending_invoices' => $invoicesPending,
            ],
        ]);
    }

    public function cashflow(Request $request): JsonResponse
    {
        $year = (int) $request->get('year', now()->year);

        return response()->json([
            'success' => true,
            'data' => [
                'year' => $year,
                'months' => collect(range(1, 12))->map(fn ($m) => [
                    'month' => $m,
                    'inflow' => Payment::whereYear('created_at', $year)->whereMonth('created_at', $m)->sum('amount'),
                    'outflow' => Expense::whereYear('date', $year)->whereMonth('date', $m)->sum('gross_amount'),
                ]),
            ],
        ]);
    }

    public function exportJPK(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'JPK export is available via POST /accounting/reports/vat. Use the generated report to submit to tax office.',
        ]);
    }

    public function exportPIT(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'PIT export is available via POST /accounting/reports/annual. Use the generated report for annual tax declaration.',
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        $year = (int) $request->get('year', now()->year);

        return response()->json([
            'success' => true,
            'data' => [
                'year' => $year,
                'total_invoiced' => Invoice::whereYear('issue_date', $year)->sum('total_amount'),
                'total_paid' => Payment::whereYear('created_at', $year)->sum('amount'),
                'total_expenses' => Expense::whereYear('date', $year)->sum('gross_amount'),
                'invoices_count' => Invoice::whereYear('issue_date', $year)->count(),
                'expenses_count' => Expense::whereYear('date', $year)->count(),
                'unpaid_invoices' => Invoice::where('status', 'sent')->whereYear('issue_date', $year)->sum('total_amount'),
            ],
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Контроллер AccountingController — 16 API endpoints бухгалтерского модуля.
//
// ГЕНЕРАЦИЯ ОТЧЁТОВ:
//   POST /reports/monthly    — полный месячный отчёт (PIT+VAT+ZUS)
//   POST /reports/vat        — JPK_VAT для налоговой
//   POST /reports/zus        — ZUS DRA декларация
//   POST /reports/annual     — годовая декларация PIT
//   POST /reports/profit-loss — P&L (внутренний)
//
// УПРАВЛЕНИЕ ОТЧЁТАМИ:
//   GET  /reports           — список отчётов с фильтрами
//   GET  /reports/{id}      — детали отчёта
//   PATCH /reports/{id}/submit — пометить "отправлен в налоговую"
//
// КАЛЬКУЛЯТОР:
//   POST /calculate          — быстрый расчёт налогов
//   POST /compare-regimes    — сравнение режимов (какой выгоднее)
//
// РАСХОДЫ:
//   GET  /expenses          — список расходов
//   POST /expenses          — добавить расход
//   DELETE /expenses/{id}   — удалить расход
//
// ПЕРИОДЫ:
//   GET  /periods           — список периодов + кумулятивные итоги
//   PATCH /periods/{id}/close — закрыть период
//
// КОНФИГУРАЦИЯ:
//   GET  /tax-config        — все ставки/лимиты/дедлайны для UI
//
// Файл: app/Http/Controllers/Api/V1/AccountingController.php
// ---------------------------------------------------------------
