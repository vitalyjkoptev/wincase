<?php

// =====================================================
// FILE: n8n/workflows_registry.php
// Reference config for all 22 n8n workflows
// Used for health monitoring + documentation
// =====================================================

return [

    // =====================================================
    // LEADS MODULE (W01-W03)
    // =====================================================

    'W01' => [
        'name' => 'Lead Capture — Multi-Source Ingestion',
        'trigger' => 'Webhook',
        'frequency' => 'Real-time',
        'module' => 'leads',
        'description' => 'Receives leads from 14 sources (landing forms, WhatsApp, Telegram, phone). '
            . 'Validates data, detects duplicates, assigns priority based on service type + UTM params, '
            . 'routes to available manager via round-robin.',
        'endpoints' => [
            'POST /api/v1/leads/webhook' => 'Main webhook for all landing forms',
            'POST /api/v1/leads/whatsapp' => 'WhatsApp Business API callback',
            'POST /api/v1/leads/telegram' => 'Telegram Bot webhook',
        ],
        'integrations' => ['Landing Forms', 'WhatsApp Business', 'Telegram Bot', 'Facebook Lead Ads'],
    ],

    'W02' => [
        'name' => 'Lead Auto-Response',
        'trigger' => 'W01 completion',
        'frequency' => 'Real-time (within 30s)',
        'module' => 'leads',
        'description' => 'Sends auto-response to new lead based on preferred_language (8 languages). '
            . 'Templates: WhatsApp (Business API), Telegram, Email, SMS.',
        'integrations' => ['WhatsApp Business', 'Telegram', 'SendGrid', 'SMS API'],
    ],

    'W03' => [
        'name' => 'Lead Nurturing — Follow-up Sequence',
        'trigger' => 'Cron',
        'frequency' => 'Every 4 hours',
        'module' => 'leads',
        'description' => 'Checks leads not contacted within 24h. Sends reminders to assigned manager. '
            . 'After 48h: re-assigns to next available manager. After 72h: escalates to admin.',
    ],

    // =====================================================
    // ADS MODULE (W04-W07)
    // =====================================================

    'W04' => [
        'name' => 'Google Ads Sync',
        'trigger' => 'Cron',
        'frequency' => 'Every 6 hours',
        'module' => 'ads',
        'description' => 'Fetches Google Ads performance data via API v17. '
            . 'Metrics: impressions, clicks, cost, conversions, CTR, CPC. '
            . 'Stores in ads_performance table. Uploads offline conversions.',
        'integrations' => ['Google Ads API v17'],
    ],

    'W05' => [
        'name' => 'Meta Ads Sync',
        'trigger' => 'Cron',
        'frequency' => 'Every 6 hours',
        'module' => 'ads',
        'description' => 'Fetches Facebook + Instagram ads data via Marketing API v19.0. '
            . 'Campaigns, ad sets, ads performance. Uploads CAPI offline events.',
        'integrations' => ['Meta Marketing API v19.0', 'Conversions API (CAPI)'],
    ],

    'W06' => [
        'name' => 'TikTok Ads Sync',
        'trigger' => 'Cron',
        'frequency' => 'Every 6 hours',
        'module' => 'ads',
        'description' => 'Fetches TikTok Ads performance via Marketing API v1.3. '
            . 'Uploads offline events via Events API.',
        'integrations' => ['TikTok Marketing API v1.3'],
    ],

    'W07' => [
        'name' => 'Pinterest + YouTube Ads Sync',
        'trigger' => 'Cron',
        'frequency' => 'Daily 06:00',
        'module' => 'ads',
        'description' => 'Pinterest Ads API v5 + YouTube Ads via Google Ads API. Daily aggregation.',
        'integrations' => ['Pinterest API v5', 'Google Ads API (YouTube)'],
    ],

    // =====================================================
    // SEO MODULE (W08-W09)
    // =====================================================

    'W08' => [
        'name' => 'SEO Data Sync — GSC + GA4',
        'trigger' => 'Cron',
        'frequency' => 'Daily 07:00',
        'module' => 'seo',
        'description' => 'Google Search Console: clicks, impressions, position for 4 domains. '
            . 'Google Analytics 4: users, sessions, events for 4 properties.',
        'integrations' => ['Google Search Console API', 'GA4 Data API'],
    ],

    'W09' => [
        'name' => 'SEO Data Sync — Ahrefs + Network',
        'trigger' => 'Cron',
        'frequency' => 'Weekly Monday 08:00',
        'module' => 'seo',
        'description' => 'Ahrefs API v3: DA, backlinks, referring domains for 4 main + 8 satellite domains. '
            . 'Network sites article count + status check.',
        'integrations' => ['Ahrefs API v3'],
    ],

    // =====================================================
    // SOCIAL MODULE (W10-W14)
    // =====================================================

    'W10' => [
        'name' => 'Reviews Sync',
        'trigger' => 'Cron',
        'frequency' => 'Every 2 hours',
        'module' => 'brand',
        'description' => 'Syncs reviews from Google (Places API), Trustpilot (Business API), '
            . 'Facebook (Graph API). GoWork.pl scraping.',
        'integrations' => ['Google Places API', 'Trustpilot API', 'Facebook Graph API'],
    ],

    'W11' => [
        'name' => 'Social Accounts Sync',
        'trigger' => 'Cron',
        'frequency' => 'Daily 09:00',
        'module' => 'social',
        'description' => 'Syncs followers, posts count for all 8 platforms. '
            . 'Updates social_accounts table.',
        'integrations' => ['All 8 social platform APIs'],
    ],

    'W12' => [
        'name' => 'Social Post Analytics',
        'trigger' => 'Cron',
        'frequency' => 'Every 4 hours',
        'module' => 'social',
        'description' => 'Fetches analytics for posts published in last 7 days. '
            . 'Updates social_analytics: impressions, reach, likes, comments, shares.',
    ],

    'W13' => [
        'name' => 'Scheduled Post Publisher',
        'trigger' => 'Cron',
        'frequency' => 'Every 5 minutes',
        'module' => 'social',
        'description' => 'Checks social_posts with status=scheduled and scheduled_at <= now(). '
            . 'Publishes to selected platforms via SocialOrchestrationService.',
    ],

    'W14' => [
        'name' => 'Unified Inbox Poll',
        'trigger' => 'Cron',
        'frequency' => 'Every 10 minutes',
        'module' => 'social',
        'description' => 'Polls inbox for FB, IG, YouTube (comments), Telegram. '
            . 'New messages trigger push notification to assigned manager.',
    ],

    // =====================================================
    // BRAND MODULE (W15-W16)
    // =====================================================

    'W15' => [
        'name' => 'Brand Mentions Monitor',
        'trigger' => 'Cron',
        'frequency' => 'Every 3 hours',
        'module' => 'brand',
        'description' => 'Monitors web for brand mentions: "WinCase", "wincase.pro", "biuro imigracyjne wincase". '
            . 'Sources: Google Alerts API, social media search.',
    ],

    'W16' => [
        'name' => 'NAP Consistency Check',
        'trigger' => 'Cron',
        'frequency' => 'Weekly Friday 10:00',
        'module' => 'brand',
        'description' => 'Checks NAP (Name, Address, Phone) consistency across 54 directories. '
            . 'Reports mismatches to admin dashboard.',
    ],

    // =====================================================
    // ACCOUNTING MODULE (W17-W19)
    // =====================================================

    'W17' => [
        'name' => 'Bank Statement Import',
        'trigger' => 'Cron',
        'frequency' => 'Daily 22:00',
        'module' => 'accounting',
        'description' => 'Imports bank transactions via Open Banking API (PSD2). '
            . 'Auto-matches with invoices. Flags unmatched for manual review.',
    ],

    'W18' => [
        'name' => 'Invoice Generator',
        'trigger' => 'Event (POS approved)',
        'frequency' => 'Real-time',
        'module' => 'accounting',
        'description' => 'Generates invoice (Faktura VAT) when POS transaction approved. '
            . 'Auto-fills: NIP, company data, items, VAT 23%. Sends PDF via email.',
    ],

    'W19' => [
        'name' => 'Tax Report Generator',
        'trigger' => 'Cron',
        'frequency' => 'Monthly 1st at 06:00',
        'module' => 'accounting',
        'description' => 'Generates monthly tax reports: PIT advance, VAT, ZUS. '
            . 'Calculates obligations based on selected tax regime.',
    ],

    // =====================================================
    // SYSTEM WORKFLOWS (W20-W22)
    // =====================================================

    'W20' => [
        'name' => 'Document Expiry Alert',
        'trigger' => 'Cron',
        'frequency' => 'Daily 08:00',
        'module' => 'core',
        'description' => 'Checks documents expiring in 30/14/7/3/1 days. '
            . 'Sends push notification + email to client + assigned manager.',
    ],

    'W21' => [
        'name' => 'Case Deadline Alert',
        'trigger' => 'Cron',
        'frequency' => 'Daily 08:00',
        'module' => 'core',
        'description' => 'Checks cases with deadlines in 7/3/1 days. '
            . 'Sends push + email to assigned manager. Escalates overdue to admin.',
    ],

    'W22' => [
        'name' => 'System Health Monitor',
        'trigger' => 'Cron',
        'frequency' => 'Every 15 minutes',
        'module' => 'system',
        'description' => 'Checks: API health, DB connections, Redis, n8n status, disk space. '
            . 'Sends Telegram alert to admin if any check fails.',
        'integrations' => ['Telegram Bot (admin alerts)'],
    ],
];

// ---------------------------------------------------------------
// Аннотация (RU):
// Реестр 22 n8n workflows WINCASE CRM.
// Модули: Leads (W01-W03), Ads (W04-W07), SEO (W08-W09),
// Social (W10-W14), Brand (W15-W16), Accounting (W17-W19), System (W20-W22).
// Триггеры: Webhook (real-time), Cron (scheduled), Event (reactive).
// Интеграции: Google Ads, Meta, TikTok, Pinterest, YouTube, GSC, GA4, Ahrefs,
// 8 social APIs, Trustpilot, WhatsApp Business, Telegram Bot, Open Banking.
// Файл: n8n/workflows_registry.php
// ---------------------------------------------------------------
