<?php

// =====================================================
// FILE: app/Console/Kernel.php
// Laravel Scheduler — all automated tasks
// =====================================================

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // ==========================================
        // EVERY MINUTE
        // ==========================================
        // Dashboard real-time KPI refresh (Redis cache)
        $schedule->call(function () {
            app(\App\Services\Dashboard\DashboardService::class)->refreshCache();
        })->everyMinute()->name('dashboard:refresh')->withoutOverlapping();

        // ==========================================
        // EVERY 5 MINUTES
        // ==========================================
        // News: parse critical sources (PAP, UDSC, Gov.pl)
        $schedule->call(function () {
            app(\App\Services\News\NewsParserService::class)->parseByCriticalPriority();
        })->everyFiveMinutes()->name('news:parse-critical')->withoutOverlapping();

        // News: auto-publish ready articles
        $schedule->call(function () {
            app(\App\Services\News\NewsPublisherService::class)->publishReady();
        })->everyFiveMinutes()->name('news:publish')->withoutOverlapping();

        // ==========================================
        // EVERY 10 MINUTES
        // ==========================================
        // News: AI rewrite batch (10 pending articles)
        $schedule->call(function () {
            app(\App\Services\News\AIRewriterService::class)->rewriteBatch();
        })->everyTenMinutes()->name('news:rewrite-batch')->withoutOverlapping();

        // ==========================================
        // EVERY 15 MINUTES
        // ==========================================
        // News: parse high+medium priority sources
        $schedule->call(function () {
            $parser = app(\App\Services\News\NewsParserService::class);
            $parser->parseByPriority('high');
            $parser->parseByPriority('medium');
        })->everyFifteenMinutes()->name('news:parse-high-medium')->withoutOverlapping();

        // ==========================================
        // EVERY 30 MINUTES
        // ==========================================
        // News: parse low priority (sport, tech)
        $schedule->call(function () {
            app(\App\Services\News\NewsParserService::class)->parseByPriority('low');
        })->everyThirtyMinutes()->name('news:parse-low')->withoutOverlapping();

        // Ads: sync platform data (Google, Meta, TikTok)
        $schedule->call(function () {
            app(\App\Services\Ads\AdsService::class)->syncAllPlatforms();
        })->everyThirtyMinutes()->name('ads:sync')->withoutOverlapping();

        // ==========================================
        // HOURLY
        // ==========================================
        // Social media: sync analytics + inbox
        $schedule->call(function () {
            app(\App\Services\Social\SocialService::class)->syncAllPlatforms();
        })->hourly()->name('social:sync');

        // SEO: sync GSC + GA4 data
        $schedule->call(function () {
            app(\App\Services\SEO\SEOService::class)->syncSearchConsole();
            app(\App\Services\SEO\SEOService::class)->syncAnalytics();
        })->hourly()->name('seo:sync');

        // Brand: check reputation & reviews
        $schedule->call(function () {
            app(\App\Services\Brand\BrandService::class)->syncReviews();
        })->hourly()->name('brand:reviews');

        // ==========================================
        // DAILY
        // ==========================================
        // Document expiry notifications (7, 14, 30 days ahead)
        $schedule->call(function () {
            $notif = app(\App\Services\Notifications\NotificationService::class);
            $docs = \App\Models\Document::whereIn(
                \DB::raw('DATEDIFF(expires_at, NOW())'), [7, 14, 30]
            )->with('client')->get();

            foreach ($docs as $doc) {
                $days = now()->diffInDays($doc->expires_at);
                $notif->send([
                    'type' => 'document_expiring',
                    'title' => "Document Expiring: {$doc->document_type}",
                    'body' => "{$doc->client->name}'s {$doc->document_type} expires in {$days} days.",
                    'channels' => $days <= 7 ? ['in_app', 'push', 'email', 'whatsapp'] : ['in_app', 'push'],
                    'priority' => $days <= 7 ? 'high' : 'normal',
                    'client_id' => $doc->client_id,
                ]);
            }
        })->dailyAt('08:00')->name('documents:expiry-check');

        // Task overdue notifications
        $schedule->call(function () {
            $notif = app(\App\Services\Notifications\NotificationService::class);
            $tasks = \App\Models\Task::where('status', '!=', 'completed')
                ->where('due_date', '<', now())->with('assignedTo')->get();

            foreach ($tasks as $task) {
                if (!$task->assigned_to) continue;
                $notif->send([
                    'type' => 'task_overdue',
                    'title' => "Overdue Task: {$task->title}",
                    'body' => "Task \"{$task->title}\" is overdue (due: {$task->due_date}).",
                    'user_id' => $task->assigned_to,
                    'channels' => ['in_app', 'push', 'email'],
                    'priority' => 'high',
                ]);
            }
        })->dailyAt('09:00')->name('tasks:overdue-check');

        // Case deadline reminders (7 days ahead)
        $schedule->call(function () {
            $notif = app(\App\Services\Notifications\NotificationService::class);
            $cases = \App\Models\CrmCase::whereNotIn('status', ['completed', 'closed'])
                ->where('deadline', '>=', now())
                ->where('deadline', '<=', now()->addDays(7))
                ->get();

            foreach ($cases as $case) {
                $days = now()->diffInDays($case->deadline);
                $notif->send([
                    'type' => 'case_deadline',
                    'title' => "Case Deadline: {$case->case_number}",
                    'body' => "Case {$case->case_number} deadline in {$days} days.",
                    'user_id' => $case->assigned_to,
                    'channels' => ['in_app', 'push', 'email', 'telegram'],
                    'priority' => 'high',
                ]);
            }
        })->dailyAt('08:30')->name('cases:deadline-check');

        // Unassigned leads alert (older than 2 hours)
        $schedule->call(function () {
            $count = \App\Models\Lead::whereNull('assigned_to')
                ->where('created_at', '<', now()->subHours(2))
                ->where('status', 'new')->count();

            if ($count > 0) {
                app(\App\Services\Notifications\NotificationService::class)->send([
                    'type' => 'lead_unassigned',
                    'title' => "Unassigned Leads Alert",
                    'body' => "{$count} leads unassigned for 2+ hours. Please assign managers.",
                    'channels' => ['in_app', 'telegram'],
                    'priority' => 'urgent',
                ]);
            }
        })->everyThreeHours()->name('leads:unassigned-alert');

        // Scheduled reports
        $schedule->call(function () {
            app(\App\Services\Reports\ScheduledReportsService::class)->runDue();
        })->dailyAt('07:00')->name('reports:scheduled');

        // Audit logs cleanup (older than 365 days)
        $schedule->call(function () {
            \App\Models\AuditLog::where('created_at', '<', now()->subYear())->delete();
        })->dailyAt('03:00')->name('audit:cleanup');

        // News feed logs cleanup (older than 30 days)
        $schedule->call(function () {
            \App\Models\NewsFeedLog::where('created_at', '<', now()->subDays(30))->delete();
        })->dailyAt('03:15')->name('news:feed-cleanup');

        // ==========================================
        // WEEKLY
        // ==========================================
        // SEO: keyword positions check
        $schedule->call(function () {
            app(\App\Services\SEO\SEOService::class)->checkKeywordPositions();
        })->weeklyOn(1, '06:00')->name('seo:keywords'); // Monday

        // Brand: NAP consistency check across 54 directories
        $schedule->call(function () {
            app(\App\Services\Brand\BrandService::class)->napConsistencyCheck();
        })->weeklyOn(2, '06:00')->name('brand:nap-check'); // Tuesday

        // Ahrefs: backlink data sync
        $schedule->call(function () {
            app(\App\Services\SEO\SEOService::class)->syncAhrefs();
        })->weeklyOn(3, '06:00')->name('seo:ahrefs'); // Wednesday

        // System: optimization
        $schedule->call(function () {
            \Artisan::call('optimize:clear');
            \Artisan::call('config:cache');
            \Artisan::call('route:cache');
            \Artisan::call('view:cache');
        })->weeklyOn(7, '04:00')->name('system:optimize'); // Sunday

        // ==========================================
        // MONTHLY
        // ==========================================
        // Generate monthly financial report
        $schedule->call(function () {
            $reports = app(\App\Services\Reports\ReportingService::class);
            $reports->generate('financial', 'pdf', [
                'date_from' => now()->subMonth()->startOfMonth()->toDateString(),
                'date_to' => now()->subMonth()->endOfMonth()->toDateString(),
            ]);
        })->monthlyOn(1, '07:00')->name('reports:monthly-financial');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Laravel Scheduler — 20+ автоматических задач:
// Каждую минуту: Dashboard KPI refresh (Redis).
// Каждые 5 мин: News parse critical, News publish.
// Каждые 10 мин: News AI rewrite batch.
// Каждые 15 мин: News parse high+medium.
// Каждые 30 мин: News parse low, Ads sync.
// Каждый час: Social sync, SEO sync, Brand reviews.
// Ежедневно: Doc expiry (8:00), Task overdue (9:00), Case deadline (8:30),
//            Unassigned leads (3h), Scheduled reports (7:00), Audit cleanup (3:00).
// Еженедельно: SEO keywords (Mon), NAP check (Tue), Ahrefs (Wed), System optimize (Sun).
// Ежемесячно: Financial report (1-го числа).
// Файл: app/Console/Kernel.php
// ---------------------------------------------------------------
