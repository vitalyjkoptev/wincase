<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class N8nWorkflowsSeeder extends Seeder
{
    public function run(): void
    {
        $workflows = [
            // LEADS MODULE
            ['code' => 'W01', 'name' => 'Lead Capture — Multi-Source Ingestion', 'module' => 'leads', 'trigger_type' => 'webhook', 'frequency' => 'Real-time'],
            ['code' => 'W02', 'name' => 'Lead Auto-Response', 'module' => 'leads', 'trigger_type' => 'webhook', 'frequency' => 'Real-time (30s)'],
            ['code' => 'W03', 'name' => 'Lead Nurturing — Follow-up Sequence', 'module' => 'leads', 'trigger_type' => 'cron', 'frequency' => 'Every 4 hours'],

            // ADS MODULE
            ['code' => 'W04', 'name' => 'Google Ads Sync', 'module' => 'ads', 'trigger_type' => 'cron', 'frequency' => 'Every 6 hours'],
            ['code' => 'W05', 'name' => 'Meta Ads Sync', 'module' => 'ads', 'trigger_type' => 'cron', 'frequency' => 'Every 6 hours'],
            ['code' => 'W06', 'name' => 'TikTok Ads Sync', 'module' => 'ads', 'trigger_type' => 'cron', 'frequency' => 'Every 6 hours'],
            ['code' => 'W07', 'name' => 'Pinterest + YouTube Ads Sync', 'module' => 'ads', 'trigger_type' => 'cron', 'frequency' => 'Daily 06:00'],

            // SEO MODULE
            ['code' => 'W08', 'name' => 'SEO Data Sync — GSC + GA4', 'module' => 'seo', 'trigger_type' => 'cron', 'frequency' => 'Daily 07:00'],
            ['code' => 'W09', 'name' => 'SEO Data Sync — Ahrefs + Network', 'module' => 'seo', 'trigger_type' => 'cron', 'frequency' => 'Weekly Mon 08:00'],

            // SOCIAL / BRAND MODULE
            ['code' => 'W10', 'name' => 'Reviews Sync (4 platforms)', 'module' => 'brand', 'trigger_type' => 'cron', 'frequency' => 'Every 2 hours'],
            ['code' => 'W11', 'name' => 'Social Accounts Sync', 'module' => 'social', 'trigger_type' => 'cron', 'frequency' => 'Daily 09:00'],
            ['code' => 'W12', 'name' => 'Social Post Analytics', 'module' => 'social', 'trigger_type' => 'cron', 'frequency' => 'Every 4 hours'],
            ['code' => 'W13', 'name' => 'Scheduled Post Publisher', 'module' => 'social', 'trigger_type' => 'cron', 'frequency' => 'Every 5 min'],
            ['code' => 'W14', 'name' => 'Unified Inbox Poll', 'module' => 'social', 'trigger_type' => 'cron', 'frequency' => 'Every 10 min'],
            ['code' => 'W15', 'name' => 'Brand Mentions Monitor', 'module' => 'brand', 'trigger_type' => 'cron', 'frequency' => 'Every 3 hours'],
            ['code' => 'W16', 'name' => 'NAP Consistency Check', 'module' => 'brand', 'trigger_type' => 'cron', 'frequency' => 'Weekly Fri 10:00'],

            // ACCOUNTING MODULE
            ['code' => 'W17', 'name' => 'Bank Statement Import', 'module' => 'accounting', 'trigger_type' => 'cron', 'frequency' => 'Daily 22:00'],
            ['code' => 'W18', 'name' => 'Invoice Generator (POS Approved)', 'module' => 'accounting', 'trigger_type' => 'event', 'frequency' => 'Real-time'],
            ['code' => 'W19', 'name' => 'Tax Report Generator', 'module' => 'accounting', 'trigger_type' => 'cron', 'frequency' => 'Monthly 1st 06:00'],

            // SYSTEM MODULE
            ['code' => 'W20', 'name' => 'Document Expiry Alert', 'module' => 'core', 'trigger_type' => 'cron', 'frequency' => 'Daily 08:00'],
            ['code' => 'W21', 'name' => 'Case Deadline Alert', 'module' => 'core', 'trigger_type' => 'cron', 'frequency' => 'Daily 08:00'],
            ['code' => 'W22', 'name' => 'System Health Monitor', 'module' => 'system', 'trigger_type' => 'cron', 'frequency' => 'Every 15 min'],

            // NEWS V2
            ['code' => 'W28', 'name' => 'Critical News Parser (5min)', 'module' => 'news', 'trigger_type' => 'cron', 'frequency' => 'Every 5 min'],
            ['code' => 'W29', 'name' => 'High Priority Parser (10min)', 'module' => 'news', 'trigger_type' => 'cron', 'frequency' => 'Every 10 min'],
            ['code' => 'W30', 'name' => 'Medium Priority Parser (30min)', 'module' => 'news', 'trigger_type' => 'cron', 'frequency' => 'Every 30 min'],
            ['code' => 'W31', 'name' => 'Low Priority Parser (60min)', 'module' => 'news', 'trigger_type' => 'cron', 'frequency' => 'Every 60 min'],
            ['code' => 'W32', 'name' => 'AI Rewrite Engine (10min)', 'module' => 'news', 'trigger_type' => 'cron', 'frequency' => 'Every 10 min'],
            ['code' => 'W33', 'name' => 'Auto Publisher (5min)', 'module' => 'news', 'trigger_type' => 'cron', 'frequency' => 'Every 5 min'],
            ['code' => 'W34', 'name' => 'Site Health Monitor (15min)', 'module' => 'news', 'trigger_type' => 'cron', 'frequency' => 'Every 15 min'],
            ['code' => 'W35', 'name' => 'Daily News Digest (20:00)', 'module' => 'news', 'trigger_type' => 'cron', 'frequency' => 'Daily 20:00'],

            // EXTRA (active in n8n)
            ['code' => 'W36', 'name' => 'SEO Content Generator (Satellites)', 'module' => 'seo', 'trigger_type' => 'cron', 'frequency' => 'Daily'],
            ['code' => 'W37', 'name' => 'PolandPulse Auto Publisher', 'module' => 'news', 'trigger_type' => 'cron', 'frequency' => 'Every 10 min'],
        ];

        $now = now();
        foreach ($workflows as $wf) {
            DB::table('n8n_workflows')->updateOrInsert(
                ['code' => $wf['code']],
                array_merge($wf, [
                    'is_active' => false,
                    'last_status' => 'unknown',
                    'execution_count' => 0,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
