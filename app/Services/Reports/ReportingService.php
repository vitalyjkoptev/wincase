<?php

namespace App\Services\Reports;

use App\Models\{Client, CrmCase, Lead, Invoice, NewsArticle, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportingService
{
    // =====================================================
    // REPORT TYPES REGISTRY
    // =====================================================

    public static function getAvailableReports(): array
    {
        return [
            'leads_summary' => [
                'name' => 'Leads Summary',
                'description' => 'Lead acquisition, conversion, source performance',
                'formats' => ['pdf', 'xlsx', 'json'],
                'parameters' => ['date_from', 'date_to', 'source', 'status'],
            ],
            'cases_status' => [
                'name' => 'Cases Status Report',
                'description' => 'All cases by status, service type, manager workload',
                'formats' => ['pdf', 'xlsx', 'json'],
                'parameters' => ['date_from', 'date_to', 'status', 'service_type'],
            ],
            'financial' => [
                'name' => 'Financial Report',
                'description' => 'Revenue, payments, outstanding, tax summary (PIT/CIT/VAT/ZUS)',
                'formats' => ['pdf', 'xlsx', 'json'],
                'parameters' => ['date_from', 'date_to', 'period'],
            ],
            'client_portfolio' => [
                'name' => 'Client Portfolio',
                'description' => 'Active clients, nationalities, services, documents status',
                'formats' => ['pdf', 'xlsx', 'json'],
                'parameters' => ['status', 'nationality'],
            ],
            'manager_performance' => [
                'name' => 'Manager Performance',
                'description' => 'Per-manager: leads handled, cases resolved, avg response time',
                'formats' => ['pdf', 'xlsx', 'json'],
                'parameters' => ['date_from', 'date_to', 'user_id'],
            ],
            'ads_roi' => [
                'name' => 'Advertising ROI',
                'description' => 'Per-platform spend, leads, CPA, ROAS',
                'formats' => ['pdf', 'xlsx', 'json'],
                'parameters' => ['date_from', 'date_to', 'platform'],
            ],
            'news_pipeline' => [
                'name' => 'News Pipeline Report',
                'description' => 'Parsed, rewritten, published articles, plagiarism stats',
                'formats' => ['pdf', 'xlsx', 'json'],
                'parameters' => ['date_from', 'date_to'],
            ],
            'document_expiry' => [
                'name' => 'Document Expiry Tracker',
                'description' => 'Documents expiring in N days, by client and type',
                'formats' => ['pdf', 'xlsx'],
                'parameters' => ['days_ahead'],
            ],
        ];
    }

    // =====================================================
    // GENERATE REPORT — main dispatcher
    // =====================================================

    public function generate(string $reportType, string $format, array $params = []): array
    {
        $data = match ($reportType) {
            'leads_summary' => $this->buildLeadsSummary($params),
            'cases_status' => $this->buildCasesStatus($params),
            'financial' => $this->buildFinancial($params),
            'client_portfolio' => $this->buildClientPortfolio($params),
            'manager_performance' => $this->buildManagerPerformance($params),
            'ads_roi' => $this->buildAdsROI($params),
            'news_pipeline' => $this->buildNewsPipeline($params),
            'document_expiry' => $this->buildDocumentExpiry($params),
            default => throw new \InvalidArgumentException("Unknown report: {$reportType}"),
        };

        $filename = "{$reportType}_" . now()->format('Y-m-d_His');

        return match ($format) {
            'pdf' => $this->exportPDF($reportType, $data, $filename),
            'xlsx' => $this->exportXLSX($data, $filename),
            'json' => ['filename' => "{$filename}.json", 'data' => $data, 'download_url' => null],
            default => throw new \InvalidArgumentException("Unknown format: {$format}"),
        };
    }

    // =====================================================
    // LEADS SUMMARY
    // =====================================================

    protected function buildLeadsSummary(array $params): array
    {
        $from = $params['date_from'] ?? now()->startOfMonth()->toDateString();
        $to = $params['date_to'] ?? now()->toDateString();

        $leads = Lead::whereBetween('created_at', [$from, "{$to} 23:59:59"]);
        if (!empty($params['source'])) $leads->where('source', $params['source']);

        $total = (clone $leads)->count();

        return [
            'report_title' => 'Leads Summary Report',
            'period' => "{$from} — {$to}",
            'generated_at' => now()->format('Y-m-d H:i'),
            'summary' => [
                'total_leads' => $total,
                'converted' => (clone $leads)->where('status', 'paid')->count(),
                'conversion_rate' => $total > 0 ? round(((clone $leads)->where('status', 'paid')->count() / $total) * 100, 1) : 0,
                'rejected' => (clone $leads)->where('status', 'rejected')->count(),
            ],
            'by_source' => (clone $leads)->selectRaw('source, COUNT(*) as count')
                ->groupBy('source')->orderByDesc('count')->get()->toArray(),
            'by_status' => (clone $leads)->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')->get()->toArray(),
            'by_service' => (clone $leads)->selectRaw('service_type, COUNT(*) as count')
                ->groupBy('service_type')->orderByDesc('count')->get()->toArray(),
            'by_language' => (clone $leads)->selectRaw('language, COUNT(*) as count')
                ->groupBy('language')->get()->toArray(),
            'daily_trend' => (clone $leads)->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')->orderBy('date')->get()->toArray(),
        ];
    }

    // =====================================================
    // CASES STATUS
    // =====================================================

    protected function buildCasesStatus(array $params): array
    {
        $from = $params['date_from'] ?? now()->subMonths(3)->toDateString();
        $to = $params['date_to'] ?? now()->toDateString();

        $cases = CrmCase::whereBetween('created_at', [$from, "{$to} 23:59:59"]);

        return [
            'report_title' => 'Cases Status Report',
            'period' => "{$from} — {$to}",
            'generated_at' => now()->format('Y-m-d H:i'),
            'summary' => [
                'total' => (clone $cases)->count(),
                'active' => (clone $cases)->where('status', 'active')->count(),
                'completed' => (clone $cases)->where('status', 'completed')->count(),
                'overdue' => (clone $cases)->where('deadline', '<', now())->whereNotIn('status', ['completed', 'closed'])->count(),
            ],
            'by_status' => (clone $cases)->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')->get()->toArray(),
            'by_service_type' => (clone $cases)->selectRaw('service_type, COUNT(*) as count, AVG(DATEDIFF(COALESCE(updated_at, NOW()), created_at)) as avg_days')
                ->groupBy('service_type')->get()->toArray(),
            'by_manager' => DB::table('cases')
                ->join('users', 'cases.assigned_to', '=', 'users.id')
                ->selectRaw('users.name as manager, COUNT(*) as total, SUM(CASE WHEN cases.status = "completed" THEN 1 ELSE 0 END) as completed')
                ->whereBetween('cases.created_at', [$from, "{$to} 23:59:59"])
                ->groupBy('users.name')
                ->get()->toArray(),
            'upcoming_deadlines' => CrmCase::where('deadline', '>=', now())
                ->where('deadline', '<=', now()->addDays(14))
                ->whereNotIn('status', ['completed', 'closed'])
                ->orderBy('deadline')
                ->limit(20)
                ->get(['id', 'case_number', 'service_type', 'status', 'deadline', 'assigned_to'])
                ->toArray(),
        ];
    }

    // =====================================================
    // FINANCIAL REPORT
    // =====================================================

    protected function buildFinancial(array $params): array
    {
        $from = $params['date_from'] ?? now()->startOfMonth()->toDateString();
        $to = $params['date_to'] ?? now()->toDateString();

        $invoices = Invoice::whereBetween('created_at', [$from, "{$to} 23:59:59"]);

        $revenue = (clone $invoices)->where('status', 'paid')->sum('total_amount');
        $outstanding = (clone $invoices)->where('status', 'pending')->sum('total_amount');
        $vatCollected = (clone $invoices)->where('status', 'paid')->sum('vat_amount');

        return [
            'report_title' => 'Financial Report',
            'period' => "{$from} — {$to}",
            'generated_at' => now()->format('Y-m-d H:i'),
            'summary' => [
                'total_revenue' => round($revenue, 2),
                'outstanding' => round($outstanding, 2),
                'vat_collected' => round($vatCollected, 2),
                'net_revenue' => round($revenue - $vatCollected, 2),
                'invoices_count' => (clone $invoices)->count(),
                'avg_invoice' => round($revenue / max((clone $invoices)->where('status', 'paid')->count(), 1), 2),
            ],
            'by_payment_method' => (clone $invoices)->where('status', 'paid')
                ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
                ->groupBy('payment_method')->get()->toArray(),
            'by_service_type' => (clone $invoices)->where('status', 'paid')
                ->selectRaw('service_type, COUNT(*) as count, SUM(total_amount) as total')
                ->groupBy('service_type')->orderByDesc('total')->get()->toArray(),
            'monthly_trend' => (clone $invoices)->where('status', 'paid')
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as revenue, SUM(vat_amount) as vat')
                ->groupBy('month')->orderBy('month')->get()->toArray(),
        ];
    }

    // =====================================================
    // CLIENT PORTFOLIO
    // =====================================================

    protected function buildClientPortfolio(array $params): array
    {
        $clients = Client::query();
        if (!empty($params['status'])) $clients->where('status', $params['status']);
        if (!empty($params['nationality'])) $clients->where('nationality', $params['nationality']);

        return [
            'report_title' => 'Client Portfolio Report',
            'generated_at' => now()->format('Y-m-d H:i'),
            'summary' => [
                'total_clients' => (clone $clients)->count(),
                'active' => (clone $clients)->where('status', 'active')->count(),
            ],
            'by_nationality' => (clone $clients)->selectRaw('nationality, COUNT(*) as count')
                ->groupBy('nationality')->orderByDesc('count')->limit(15)->get()->toArray(),
            'by_language' => (clone $clients)->selectRaw('preferred_language, COUNT(*) as count')
                ->groupBy('preferred_language')->get()->toArray(),
        ];
    }

    // =====================================================
    // MANAGER PERFORMANCE
    // =====================================================

    protected function buildManagerPerformance(array $params): array
    {
        $from = $params['date_from'] ?? now()->startOfMonth()->toDateString();
        $to = $params['date_to'] ?? now()->toDateString();

        $managers = User::whereIn('role', ['boss', 'staff'])->where('status', 'active')->get();

        $performance = [];
        foreach ($managers as $m) {
            $leads = Lead::where('assigned_to', $m->id)->whereBetween('created_at', [$from, "{$to} 23:59:59"]);
            $cases = CrmCase::where('assigned_to', $m->id)->whereBetween('created_at', [$from, "{$to} 23:59:59"]);

            $performance[] = [
                'manager' => $m->name,
                'role' => $m->role,
                'leads_assigned' => (clone $leads)->count(),
                'leads_converted' => (clone $leads)->where('status', 'paid')->count(),
                'cases_active' => (clone $cases)->whereNotIn('status', ['completed', 'closed'])->count(),
                'cases_completed' => (clone $cases)->where('status', 'completed')->count(),
                'tasks_completed' => $m->tasks()->where('status', 'completed')
                    ->whereBetween('completed_at', [$from, "{$to} 23:59:59"])->count(),
                'tasks_overdue' => $m->tasks()->where('status', '!=', 'completed')
                    ->where('due_date', '<', now())->count(),
            ];
        }

        return [
            'report_title' => 'Manager Performance Report',
            'period' => "{$from} — {$to}",
            'generated_at' => now()->format('Y-m-d H:i'),
            'managers' => $performance,
        ];
    }

    // =====================================================
    // ADS ROI
    // =====================================================

    protected function buildAdsROI(array $params): array
    {
        $from = $params['date_from'] ?? now()->subDays(30)->toDateString();
        $to = $params['date_to'] ?? now()->toDateString();

        $campaigns = DB::table('ad_campaigns')
            ->whereBetween('date', [$from, $to])
            ->selectRaw('platform, SUM(spend) as spend, SUM(impressions) as impressions, SUM(clicks) as clicks, SUM(conversions) as conversions')
            ->groupBy('platform')
            ->get();

        $roi = $campaigns->map(function ($c) {
            return [
                'platform' => $c->platform,
                'spend' => round($c->spend, 2),
                'impressions' => $c->impressions,
                'clicks' => $c->clicks,
                'conversions' => $c->conversions,
                'ctr' => $c->impressions > 0 ? round(($c->clicks / $c->impressions) * 100, 2) : 0,
                'cpa' => $c->conversions > 0 ? round($c->spend / $c->conversions, 2) : 0,
                'cpc' => $c->clicks > 0 ? round($c->spend / $c->clicks, 2) : 0,
            ];
        })->toArray();

        return [
            'report_title' => 'Advertising ROI Report',
            'period' => "{$from} — {$to}",
            'generated_at' => now()->format('Y-m-d H:i'),
            'total_spend' => round($campaigns->sum('spend'), 2),
            'total_conversions' => $campaigns->sum('conversions'),
            'platforms' => $roi,
        ];
    }

    // =====================================================
    // NEWS PIPELINE
    // =====================================================

    protected function buildNewsPipeline(array $params): array
    {
        $from = $params['date_from'] ?? now()->subDays(7)->toDateString();
        $to = $params['date_to'] ?? now()->toDateString();

        $articles = NewsArticle::whereBetween('created_at', [$from, "{$to} 23:59:59"]);

        return [
            'report_title' => 'News Pipeline Report',
            'period' => "{$from} — {$to}",
            'generated_at' => now()->format('Y-m-d H:i'),
            'summary' => [
                'total_parsed' => (clone $articles)->count(),
                'published' => (clone $articles)->where('status', 'published')->count(),
                'avg_plagiarism' => round((clone $articles)->whereNotNull('plagiarism_score')->avg('plagiarism_score') ?? 0, 1),
            ],
            'by_status' => (clone $articles)->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')->get()->toArray(),
            'by_source' => (clone $articles)->selectRaw('source_name, COUNT(*) as count')
                ->groupBy('source_name')->orderByDesc('count')->limit(10)->get()->toArray(),
            'by_category' => (clone $articles)->selectRaw('category, COUNT(*) as count')
                ->groupBy('category')->get()->toArray(),
        ];
    }

    // =====================================================
    // DOCUMENT EXPIRY
    // =====================================================

    protected function buildDocumentExpiry(array $params): array
    {
        $days = (int) ($params['days_ahead'] ?? 30);

        $docs = DB::table('documents')
            ->join('clients', 'documents.client_id', '=', 'clients.id')
            ->where('documents.expires_at', '>=', now())
            ->where('documents.expires_at', '<=', now()->addDays($days))
            ->select('documents.*', 'clients.name as client_name')
            ->orderBy('documents.expires_at')
            ->get()
            ->toArray();

        return [
            'report_title' => "Document Expiry Tracker (next {$days} days)",
            'generated_at' => now()->format('Y-m-d H:i'),
            'total_expiring' => count($docs),
            'documents' => $docs,
        ];
    }

    // =====================================================
    // EXPORT PDF (DomPDF)
    // =====================================================

    protected function exportPDF(string $type, array $data, string $filename): array
    {
        $html = view("reports.{$type}", ['data' => $data])->render();
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
        $path = "reports/{$filename}.pdf";
        Storage::disk('local')->put($path, $pdf->output());

        return [
            'filename' => "{$filename}.pdf",
            'path' => $path,
            'download_url' => route('reports.download', ['path' => $path]),
            'data' => $data,
        ];
    }

    // =====================================================
    // EXPORT XLSX (PhpSpreadsheet)
    // =====================================================

    protected function exportXLSX(array $data, string $filename): array
    {
        // Using PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr($data['report_title'] ?? 'Report', 0, 31));

        // Header
        $sheet->setCellValue('A1', $data['report_title'] ?? 'Report');
        $sheet->setCellValue('A2', 'Period: ' . ($data['period'] ?? 'N/A'));
        $sheet->setCellValue('A3', 'Generated: ' . ($data['generated_at'] ?? now()->format('Y-m-d H:i')));

        // Summary
        $row = 5;
        if (isset($data['summary'])) {
            $sheet->setCellValue("A{$row}", 'SUMMARY');
            $row++;
            foreach ($data['summary'] as $key => $value) {
                $sheet->setCellValue("A{$row}", ucwords(str_replace('_', ' ', $key)));
                $sheet->setCellValue("B{$row}", $value);
                $row++;
            }
        }

        // Data tables
        foreach ($data as $key => $section) {
            if (!is_array($section) || in_array($key, ['report_title', 'period', 'generated_at', 'summary'])) continue;
            if (empty($section)) continue;

            $row += 2;
            $sheet->setCellValue("A{$row}", strtoupper(str_replace('_', ' ', $key)));
            $row++;

            // Auto-detect columns from first row
            $first = reset($section);
            if (is_array($first)) {
                $col = 'A';
                foreach (array_keys($first) as $header) {
                    $sheet->setCellValue("{$col}{$row}", ucwords(str_replace('_', ' ', $header)));
                    $col++;
                }
                $row++;
                foreach ($section as $item) {
                    $col = 'A';
                    foreach ($item as $val) {
                        $sheet->setCellValue("{$col}{$row}", is_array($val) ? json_encode($val) : $val);
                        $col++;
                    }
                    $row++;
                }
            }
        }

        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $path = "reports/{$filename}.xlsx";
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempPath = storage_path("app/{$path}");
        @mkdir(dirname($tempPath), 0755, true);
        $writer->save($tempPath);

        return [
            'filename' => "{$filename}.xlsx",
            'path' => $path,
            'download_url' => route('reports.download', ['path' => $path]),
            'data' => $data,
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// ReportingService — 8 типов отчётов + PDF + XLSX + JSON export.
// leads_summary: by source, status, service, language, daily trend.
// cases_status: by status, service_type, manager, upcoming deadlines.
// financial: revenue, VAT, outstanding, by payment method, monthly trend.
// client_portfolio: by nationality, language.
// manager_performance: leads/cases/tasks per manager.
// ads_roi: per-platform spend, CPA, CPC, CTR, ROAS.
// news_pipeline: parsed/published, plagiarism stats, by source/category.
// document_expiry: expiring docs in N days.
// PDF (DomPDF), XLSX (PhpSpreadsheet), JSON.
// Файл: app/Services/Reports/ReportingService.php
// ---------------------------------------------------------------
