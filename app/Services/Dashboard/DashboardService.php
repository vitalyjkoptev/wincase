<?php

namespace App\Services\Dashboard;

use App\Models\Client;
use App\Models\ClientCase;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    protected function isSqlite(): bool
    {
        return DB::getDriverName() === 'sqlite';
    }

    protected function dateFormat(string $column, string $format): string
    {
        if ($this->isSqlite()) {
            return match ($format) {
                '%Y-%m' => "strftime('%Y-%m', {$column})",
                '%Y-%m-%d' => "date({$column})",
                default => $column,
            };
        }
        return "DATE_FORMAT({$column}, \"{$format}\")";
    }

    // =====================================================
    // KPI BAR — 12 cards for header
    // =====================================================

    /**
     * Top-level KPI bar visible on every dashboard page.
     * 12 cards updated every 60 seconds via Reverb WebSocket.
     */
    public function getKpiBar(): array
    {
        try {
        $today = now()->toDateString();
        $weekStart = now()->startOfWeek()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        $month30 = now()->subDays(30)->toDateString();

        return [
            'today_leads' => Lead::whereDate('created_at', $today)->count(),

            'active_cases' => ClientCase::where('status', 'active')->count(),

            'monthly_revenue' => (float) Invoice::where('status', 'paid')
                ->where('paid_date', '>=', $monthStart)
                ->sum('total_amount'),

            'avg_response_min' => (float) Lead::whereNotNull('first_contact_at')
                ->where('created_at', '>=', $month30)
                ->selectRaw(
                    $this->isSqlite()
                        ? 'AVG((julianday(first_contact_at) - julianday(created_at)) * 1440) as avg_min'
                        : 'AVG(TIMESTAMPDIFF(MINUTE, created_at, first_contact_at)) as avg_min'
                )
                ->value('avg_min') ?? 0,

            'ad_spend_7d' => (float) DB::table('ads_performance')
                ->where('date', '>=', now()->subDays(7)->toDateString())
                ->sum('cost'),

            'organic_users_7d' => (int) DB::table('seo_data')
                ->where('source', 'ga4')
                ->where('date', '>=', now()->subDays(7)->toDateString())
                ->sum('users'),

            'social_followers' => (int) DB::table('social_accounts')->sum('followers'),

            'conversion_rate_30d' => $this->calcConversionRate($month30),

            'pending_tasks' => Task::where('status', 'pending')
                ->where('due_date', '<=', now()->addDays(3)->toDateString())
                ->count(),

            'active_clients' => Client::where('status', 'active')->count(),

            'pos_pending' => (int) DB::table('pos_transactions')
                ->whereIn('status', ['received', 'under_review'])
                ->count(),

            'monthly_tax_burden' => (float) DB::table('tax_reports')
                ->where('period_start', '>=', $monthStart)
                ->where('status', 'calculated')
                ->sum('tax_amount'),
        ];
        } catch (\Throwable $e) {
            report($e);
            return ['today_leads' => 0, 'active_cases' => 0, 'monthly_revenue' => 0, 'avg_response_min' => 0, 'ad_spend_7d' => 0, 'organic_users_7d' => 0, 'social_followers' => 0, 'conversion_rate_30d' => 0, 'pending_tasks' => 0, 'active_clients' => 0, 'pos_pending' => 0, 'monthly_tax_burden' => 0];
        }
    }

    // =====================================================
    // SECTION 1: Cases
    // =====================================================

    public function getCasesSection(): array
    {
        try {
        $byStatus = ClientCase::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $managerLoad = ClientCase::where('status', 'active')
            ->selectRaw('assigned_to, COUNT(*) as count')
            ->groupBy('assigned_to')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();

        $deadlineAlerts = ClientCase::where('status', 'active')
            ->whereNotNull('deadline')
            ->where('deadline', '<=', now()->addDays(7)->toDateString())
            ->orderBy('deadline')
            ->limit(10)
            ->get(['id', 'client_id', 'service_type', 'deadline', 'assigned_to'])
            ->toArray();

        return [
            'by_status' => $byStatus,
            'total_active' => array_sum($byStatus),
            'manager_workload' => $managerLoad,
            'deadline_alerts' => $deadlineAlerts,
        ];
        } catch (\Throwable $e) { report($e); return ['by_status' => [], 'total_active' => 0, 'manager_workload' => [], 'deadline_alerts' => []]; }
    }

    // =====================================================
    // SECTION 2: Leads & Funnel
    // =====================================================

    public function getLeadsSection(): array
    {
        try {
        $today = now()->toDateString();
        $month30 = now()->subDays(30)->toDateString();

        // Funnel stages
        $funnel = Lead::where('created_at', '>=', $month30)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Daily trend (last 14 days)
        $dailyTrend = Lead::where('created_at', '>=', now()->subDays(14)->toDateString())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        // Source breakdown
        $bySource = Lead::where('created_at', '>=', $month30)
            ->selectRaw('source, COUNT(*) as count')
            ->groupBy('source')
            ->orderByDesc('count')
            ->get()
            ->toArray();

        return [
            'funnel' => $funnel,
            'total_30d' => array_sum($funnel),
            'today' => Lead::whereDate('created_at', $today)->count(),
            'unassigned' => Lead::whereNull('assigned_to')
                ->whereIn('status', ['new', 'contacted'])
                ->count(),
            'daily_trend' => $dailyTrend,
            'by_source' => $bySource,
            'conversion_rate' => $this->calcConversionRate($month30),
        ];
        } catch (\Throwable $e) { report($e); return ['funnel' => [], 'total_30d' => 0, 'today' => 0, 'unassigned' => 0, 'daily_trend' => [], 'by_source' => [], 'conversion_rate' => 0]; }
    }

    // =====================================================
    // SECTION 3: Finance & POS
    // =====================================================

    public function getFinanceSection(): array
    {
        try {
        $monthStart = now()->startOfMonth()->toDateString();
        $month30 = now()->subDays(30)->toDateString();

        // Revenue by month (last 6 months)
        $monthExpr = $this->dateFormat('paid_date', '%Y-%m');
        $revenueChart = Invoice::where('status', 'paid')
            ->where('paid_date', '>=', now()->subMonths(6)->startOfMonth()->toDateString())
            ->selectRaw("{$monthExpr} as month, SUM(total_amount) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->toArray();

        // Invoice status breakdown
        $invoiceStatus = Invoice::selectRaw('status, COUNT(*) as count, SUM(total_amount) as amount')
            ->groupBy('status')
            ->get()
            ->toArray();

        // POS summary
        $posSummary = DB::table('pos_transactions')
            ->where('created_at', '>=', $monthStart)
            ->selectRaw('
                COUNT(*) as total_transactions,
                SUM(CASE WHEN status = "approved" OR status = "invoiced" THEN amount ELSE 0 END) as approved_amount,
                SUM(CASE WHEN status IN ("received", "under_review") THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN payment_method = "cash" THEN amount ELSE 0 END) as cash_total,
                SUM(CASE WHEN payment_method = "card" THEN amount ELSE 0 END) as card_total
            ')
            ->first();

        return [
            'revenue_chart' => $revenueChart,
            'monthly_revenue' => (float) Invoice::where('status', 'paid')
                ->where('paid_date', '>=', $monthStart)->sum('total_amount'),
            'invoice_status' => $invoiceStatus,
            'pos' => [
                'total_transactions' => (int) ($posSummary->total_transactions ?? 0),
                'approved_amount' => (float) ($posSummary->approved_amount ?? 0),
                'pending_count' => (int) ($posSummary->pending_count ?? 0),
                'cash_total' => (float) ($posSummary->cash_total ?? 0),
                'card_total' => (float) ($posSummary->card_total ?? 0),
            ],
        ];
        } catch (\Throwable $e) { report($e); return ['revenue_chart' => [], 'monthly_revenue' => 0, 'invoice_status' => [], 'pos' => ['total_transactions' => 0, 'approved_amount' => 0, 'pending_count' => 0, 'cash_total' => 0, 'card_total' => 0]]; }
    }

    // =====================================================
    // SECTION 4: Ads Performance
    // =====================================================

    public function getAdsSection(): array
    {
        try {
        $last7 = now()->subDays(7)->toDateString();
        $last30 = now()->subDays(30)->toDateString();

        // Platform overview (7 days)
        $platforms = DB::table('ads_performance')
            ->where('date', '>=', $last7)
            ->selectRaw('
                platform,
                SUM(cost) as spend,
                SUM(leads_count) as leads,
                SUM(clicks) as clicks,
                SUM(impressions) as impressions,
                CASE WHEN SUM(leads_count) > 0 THEN SUM(cost)/SUM(leads_count) ELSE 0 END as cpl
            ')
            ->groupBy('platform')
            ->orderByDesc('spend')
            ->get()
            ->toArray();

        // Daily spend trend (30 days)
        $spendTrend = DB::table('ads_performance')
            ->where('date', '>=', $last30)
            ->selectRaw('date, SUM(cost) as spend, SUM(leads_count) as leads')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        // Budget usage this month
        $monthSpend = DB::table('ads_performance')
            ->where('date', '>=', now()->startOfMonth()->toDateString())
            ->selectRaw('platform, SUM(cost) as spend')
            ->groupBy('platform')
            ->pluck('spend', 'platform')
            ->toArray();

        $budgetPlan = config('ads.budget_plan', []);
        $totalBudget = array_sum($budgetPlan);
        $totalSpent = array_sum($monthSpend);

        return [
            'platforms_7d' => $platforms,
            'spend_trend_30d' => $spendTrend,
            'budget' => [
                'total_planned' => $totalBudget,
                'total_spent' => round($totalSpent, 2),
                'pct_used' => $totalBudget > 0 ? round(($totalSpent / $totalBudget) * 100, 1) : 0,
            ],
            'total_spend_7d' => round(array_sum(array_column($platforms, 'spend')), 2),
            'total_leads_7d' => (int) array_sum(array_column($platforms, 'leads')),
        ];
        } catch (\Throwable $e) { report($e); return ['platforms_7d' => [], 'spend_trend_30d' => [], 'budget' => ['total_planned' => 0, 'total_spent' => 0, 'pct_used' => 0], 'total_spend_7d' => 0, 'total_leads_7d' => 0]; }
    }

    // =====================================================
    // SECTION 5: Social Media
    // =====================================================

    public function getSocialSection(): array
    {
        try {
        $accounts = DB::table('social_accounts')
            ->select('platform', 'followers', 'posts_count', 'last_synced_at')
            ->orderByDesc('followers')
            ->get()
            ->toArray();

        $recentPosts = DB::table('social_posts')
            ->where('published_at', '>=', now()->subDays(7))
            ->count();

        $topPost = DB::table('social_posts')
            ->join('social_analytics', 'social_posts.id', '=', 'social_analytics.social_post_id')
            ->where('social_posts.published_at', '>=', now()->subDays(30))
            ->orderByDesc('social_analytics.engagement_rate')
            ->select('social_posts.platform', 'social_posts.text', 'social_analytics.engagement_rate',
                     'social_analytics.likes', 'social_analytics.impressions')
            ->first();

        return [
            'accounts' => $accounts,
            'total_followers' => (int) array_sum(array_column($accounts, 'followers')),
            'posts_last_7d' => $recentPosts,
            'platforms_active' => count(array_filter($accounts, fn ($a) => $a->last_synced_at && $a->last_synced_at >= now()->subDay()->toIso8601String())),
            'top_post_30d' => $topPost,
        ];
        } catch (\Throwable $e) { report($e); return ['accounts' => [], 'total_followers' => 0, 'posts_last_7d' => 0, 'platforms_active' => 0, 'top_post_30d' => null]; }
    }

    // =====================================================
    // SECTION 6: SEO Dashboard
    // =====================================================

    public function getSeoSection(): array
    {
        try {
        $last7 = now()->subDays(7)->toDateString();

        $gsc = DB::table('seo_data')
            ->where('source', 'gsc')
            ->where('date', '>=', $last7)
            ->selectRaw('SUM(clicks) as clicks, SUM(impressions) as impressions, AVG(avg_position) as avg_position')
            ->first();

        $ga4 = DB::table('seo_data')
            ->where('source', 'ga4')
            ->where('date', '>=', $last7)
            ->selectRaw('SUM(users) as users, SUM(sessions) as sessions')
            ->first();

        // DA for all 4 domains
        $domainDa = DB::table('seo_data')
            ->where('source', 'ahrefs')
            ->whereIn('domain', ['wincase.pro', 'wincase-legalization.com', 'wincase-job.com', 'wincase.org'])
            ->whereRaw('date = (SELECT MAX(date) FROM seo_data WHERE source = "ahrefs" AND seo_data.domain = seo_data.domain)')
            ->select('domain', 'domain_authority', 'backlinks', 'referring_domains')
            ->get()
            ->toArray();

        // Network status
        $network = DB::table('seo_network_sites')
            ->where('status', 'active')
            ->selectRaw('COUNT(*) as total, AVG(domain_authority) as avg_da, SUM(articles_total) as articles')
            ->first();

        return [
            'gsc_7d' => [
                'clicks' => (int) ($gsc->clicks ?? 0),
                'impressions' => (int) ($gsc->impressions ?? 0),
                'avg_position' => round((float) ($gsc->avg_position ?? 0), 1),
            ],
            'ga4_7d' => [
                'users' => (int) ($ga4->users ?? 0),
                'sessions' => (int) ($ga4->sessions ?? 0),
            ],
            'domain_authority' => $domainDa,
            'network' => [
                'active_sites' => (int) ($network->total ?? 0),
                'avg_da' => round((float) ($network->avg_da ?? 0), 0),
                'total_articles' => (int) ($network->articles ?? 0),
            ],
        ];
        } catch (\Throwable $e) { report($e); return ['gsc_7d' => ['clicks' => 0, 'impressions' => 0, 'avg_position' => 0], 'ga4_7d' => ['users' => 0, 'sessions' => 0], 'domain_authority' => [], 'network' => ['active_sites' => 0, 'avg_da' => 0, 'total_articles' => 0]]; }
    }

    // =====================================================
    // SECTION 7: Accounting & Tax
    // =====================================================

    public function getAccountingSection(): array
    {
        try {
        $monthStart = now()->startOfMonth()->toDateString();
        $yearStart = now()->startOfYear()->toDateString();

        // Current month tax burden
        $monthTax = DB::table('tax_reports')
            ->where('period_start', '>=', $monthStart)
            ->where('status', 'calculated')
            ->selectRaw('report_type, tax_amount, status')
            ->get()
            ->toArray();

        // YTD cumulative
        $ytdTax = DB::table('tax_reports')
            ->where('period_start', '>=', $yearStart)
            ->whereIn('status', ['calculated', 'filed', 'paid'])
            ->selectRaw('SUM(tax_amount) as total_tax, SUM(CASE WHEN status = "paid" THEN tax_amount ELSE 0 END) as total_paid')
            ->first();

        // Upcoming deadlines
        $day = (int) now()->format('d');
        $deadlines = [];
        if ($day < 10) $deadlines[] = ['name' => 'ZUS DRA', 'date' => now()->format('Y-m-10'), 'days_left' => 10 - $day];
        if ($day < 20) $deadlines[] = ['name' => 'PIT Advance', 'date' => now()->format('Y-m-20'), 'days_left' => 20 - $day];
        if ($day < 25) $deadlines[] = ['name' => 'JPK_VAT (SAF-T)', 'date' => now()->format('Y-m-25'), 'days_left' => 25 - $day];

        // Monthly expense breakdown
        $expenses = DB::table('expenses')
            ->where('date', '>=', $monthStart)
            ->selectRaw('category, SUM(net_amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->toArray();

        return [
            'month_tax' => $monthTax,
            'month_total_burden' => round(array_sum(array_column($monthTax, 'tax_amount')), 2),
            'ytd_tax' => round((float) ($ytdTax->total_tax ?? 0), 2),
            'ytd_paid' => round((float) ($ytdTax->total_paid ?? 0), 2),
            'deadlines' => $deadlines,
            'top_expenses' => $expenses,
        ];
        } catch (\Throwable $e) { report($e); return ['month_tax' => [], 'month_total_burden' => 0, 'ytd_tax' => 0, 'ytd_paid' => 0, 'deadlines' => [], 'top_expenses' => []]; }
    }

    // =====================================================
    // SECTION 8: AI & Automation
    // =====================================================

    public function getAutomationSection(): array
    {
        try {
        return [
            'workflows_total' => 22,
            'last_sync' => [
                'ads' => DB::table('ads_performance')->max('updated_at'),
                'seo_gsc' => DB::table('seo_data')->where('source', 'gsc')->max('updated_at'),
                'seo_ga4' => DB::table('seo_data')->where('source', 'ga4')->max('updated_at'),
                'ahrefs' => DB::table('seo_data')->where('source', 'ahrefs')->max('updated_at'),
                'social' => DB::table('social_accounts')->max('last_synced_at'),
            ],
            'recent_errors' => [],
        ];
        } catch (\Throwable $e) { report($e); return ['workflows_total' => 0, 'last_sync' => [], 'recent_errors' => []]; }
    }

    // =====================================================
    // FULL DASHBOARD — All sections combined
    // =====================================================

    public function getFullDashboard(): array
    {
        return [
            'kpi' => $this->getKpiBar(),
            'cases' => $this->getCasesSection(),
            'leads' => $this->getLeadsSection(),
            'finance' => $this->getFinanceSection(),
            'ads' => $this->getAdsSection(),
            'social' => $this->getSocialSection(),
            'seo' => $this->getSeoSection(),
            'accounting' => $this->getAccountingSection(),
            'automation' => $this->getAutomationSection(),
            'generated_at' => now()->toIso8601String(),
        ];
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function calcConversionRate(string $since): float
    {
        $total = Lead::where('created_at', '>=', $since)->count();
        $converted = Lead::where('created_at', '>=', $since)
            ->whereNotNull('client_id')
            ->count();

        return $total > 0 ? round(($converted / $total) * 100, 1) : 0;
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// DashboardService — единый сервис Dashboard модуля.
// getKpiBar() — 12 KPI карточек: leads, cases, revenue, response time,
// ad spend, organic users, followers, conversion rate, tasks, clients, POS, tax.
// 8 секций: Cases (statuses, workload, deadlines), Leads (funnel, trend, sources),
// Finance (revenue chart, invoices, POS), Ads (5 platforms, budget, spend trend),
// Social (accounts, posts, top post), SEO (GSC, GA4, DA, network),
// Accounting (tax burden, deadlines, expenses), Automation (sync status).
// getFullDashboard() — все секции в одном ответе.
// Файл: app/Services/Dashboard/DashboardService.php
// ---------------------------------------------------------------
