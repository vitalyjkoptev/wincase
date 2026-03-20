<?php

// =====================================================
// FILE: n8n/news_workflows_v2.php
// 8 n8n Workflows for Enhanced News Pipeline
// Complete automation: parse → rewrite → publish → notify
// =====================================================

return [

    // =============================================
    // W28: CRITICAL NEWS PARSER (every 5 min)
    // UDSC, Gov.pl, SchengenVisaInfo, PAP, MigrantInfo
    // =============================================
    'W28_critical_parser' => [
        'name' => 'W28: Critical News Parser (5min)',
        'trigger' => 'cron',
        'schedule' => '*/5 * * * *',
        'description' => 'Parse critical immigration/government sources every 5 minutes',
        'nodes' => [
            [
                'type' => 'Schedule Trigger',
                'params' => ['cronExpression' => '*/5 * * * *'],
            ],
            [
                'type' => 'HTTP Request',
                'method' => 'POST',
                'url' => '{{ $API_URL }}/api/v1/news/parse',
                'auth' => 'bearerAuth',
                'body' => ['priority' => 'critical'],
                'note' => 'Triggers parsing of: udsc_gov, gov_pl_migration, migrant_info, schengen_visa_info, pap_rss',
            ],
            [
                'type' => 'IF',
                'condition' => '{{ $json.data.new_articles > 0 }}',
                'note' => 'Check if new articles were found',
            ],
            [
                'type' => 'HTTP Request (true branch)',
                'method' => 'POST',
                'url' => '{{ $API_URL }}/api/v1/news/rewrite-batch',
                'note' => 'Immediately rewrite new critical articles',
            ],
            [
                'type' => 'Wait',
                'duration' => 30, // seconds — wait for AI rewrite
            ],
            [
                'type' => 'HTTP Request',
                'method' => 'POST',
                'url' => '{{ $API_URL }}/api/v1/news/publish',
                'note' => 'Publish all rewritten articles with plagiarism < 15%',
            ],
            [
                'type' => 'HTTP Request (Telegram)',
                'method' => 'POST',
                'url' => 'https://api.telegram.org/bot{{ $TELEGRAM_BOT_TOKEN }}/sendMessage',
                'body' => [
                    'chat_id' => '{{ $TELEGRAM_ADMIN_CHAT_ID }}',
                    'text' => '⚡ BREAKING: {{ $json.data.published }} news published to {{ targets }}',
                    'parse_mode' => 'HTML',
                ],
            ],
        ],
    ],

    // =============================================
    // W29: HIGH PRIORITY PARSER (every 10 min)
    // Europe, Business, iGaming, Ukraine news
    // =============================================
    'W29_high_parser' => [
        'name' => 'W29: High Priority Parser (10min)',
        'trigger' => 'cron',
        'schedule' => '*/10 * * * *',
        'nodes' => [
            ['type' => 'Schedule Trigger', 'params' => ['cronExpression' => '*/10 * * * *']],
            [
                'type' => 'HTTP Request',
                'method' => 'POST',
                'url' => '{{ $API_URL }}/api/v1/news/parse',
                'body' => ['priority' => 'high'],
                'note' => 'Euronews, Politico, Reuters, BBC, iGaming Business, Gambling Insider, TechCrunch, Ukrinform, etc.',
            ],
        ],
    ],

    // =============================================
    // W30: MEDIUM PRIORITY PARSER (every 30 min)
    // Tech, Finance, Sports, Culture
    // =============================================
    'W30_medium_parser' => [
        'name' => 'W30: Medium Priority Parser (30min)',
        'trigger' => 'cron',
        'schedule' => '*/30 * * * *',
        'nodes' => [
            ['type' => 'Schedule Trigger', 'params' => ['cronExpression' => '*/30 * * * *']],
            [
                'type' => 'HTTP Request',
                'method' => 'POST',
                'url' => '{{ $API_URL }}/api/v1/news/parse',
                'body' => ['priority' => 'medium'],
                'note' => 'Wired, Ars Technica, Bankier, Money.pl, BBC Culture, Variety, etc.',
            ],
        ],
    ],

    // =============================================
    // W31: LOW PRIORITY PARSER (every 60 min)
    // Lifestyle, Trends, Education, Motorsport
    // =============================================
    'W31_low_parser' => [
        'name' => 'W31: Low Priority Parser (60min)',
        'trigger' => 'cron',
        'schedule' => '0 * * * *',
        'nodes' => [
            ['type' => 'Schedule Trigger', 'params' => ['cronExpression' => '0 * * * *']],
            [
                'type' => 'HTTP Request',
                'method' => 'POST',
                'url' => '{{ $API_URL }}/api/v1/news/parse',
                'body' => ['priority' => 'low'],
                'note' => 'Lonely Planet, Pitchfork, Motorsport, Esports, etc.',
            ],
        ],
    ],

    // =============================================
    // W32: AI REWRITE ENGINE (every 10 min)
    // Batch rewrite parsed articles
    // =============================================
    'W32_ai_rewrite' => [
        'name' => 'W32: AI Rewrite Engine (10min)',
        'trigger' => 'cron',
        'schedule' => '*/10 * * * *',
        'nodes' => [
            ['type' => 'Schedule Trigger', 'params' => ['cronExpression' => '*/10 * * * *']],
            [
                'type' => 'HTTP Request',
                'method' => 'POST',
                'url' => '{{ $API_URL }}/api/v1/news/rewrite-batch',
                'note' => 'Rewrite 10 parsed articles per run. 3-pass anti-plagiarism system.',
            ],
            [
                'type' => 'IF',
                'condition' => '{{ $json.data.needs_review > 0 }}',
            ],
            [
                'type' => 'HTTP Request (Telegram notification)',
                'url' => 'https://api.telegram.org/bot{{ $TELEGRAM_BOT_TOKEN }}/sendMessage',
                'body' => [
                    'chat_id' => '{{ $TELEGRAM_ADMIN_CHAT_ID }}',
                    'text' => '⚠️ {{ $json.data.needs_review }} articles need manual review (plagiarism > 15%)',
                ],
            ],
        ],
    ],

    // =============================================
    // W33: AUTO PUBLISHER (every 5 min)
    // Publish rewritten articles to all target sites
    // =============================================
    'W33_auto_publisher' => [
        'name' => 'W33: Auto Publisher (5min)',
        'trigger' => 'cron',
        'schedule' => '*/5 * * * *',
        'nodes' => [
            ['type' => 'Schedule Trigger', 'params' => ['cronExpression' => '*/5 * * * *']],
            [
                'type' => 'HTTP Request',
                'method' => 'POST',
                'url' => '{{ $API_URL }}/api/v1/news/publish',
                'note' => 'Publishes all articles with status=rewritten AND plagiarism < 15% to mapped target sites.',
            ],
            [
                'type' => 'IF',
                'condition' => '{{ $json.data.published > 0 }}',
            ],
            [
                'type' => 'Set Variable',
                'values' => [
                    'summary' => '📤 Published {{ $json.data.published }} articles to {{ sites }}',
                ],
            ],
        ],
    ],

    // =============================================
    // W34: SITE HEALTH MONITOR (every 15 min)
    // Check all 8 sites are accessible
    // =============================================
    'W34_site_monitor' => [
        'name' => 'W34: Site Health Monitor (15min)',
        'trigger' => 'cron',
        'schedule' => '*/15 * * * *',
        'nodes' => [
            ['type' => 'Schedule Trigger', 'params' => ['cronExpression' => '*/15 * * * *']],
            [
                'type' => 'Split In Batches',
                'items' => [
                    'https://polandpulse.news',
                    'https://wincase.pro',
                    'https://eurogamingpost.com',
                    'https://techpulse.news',
                    'https://bizeurope.news',
                    'https://sportpulse.news',
                    'https://diaspora.news',
                    'https://trendwatch.news',
                ],
            ],
            [
                'type' => 'HTTP Request',
                'method' => 'GET',
                'url' => '{{ $item }}',
                'options' => ['timeout' => 10, 'ignoreErrors' => true],
            ],
            [
                'type' => 'IF',
                'condition' => '{{ $json.statusCode !== 200 }}',
                'note' => 'Alert if site is down',
            ],
            [
                'type' => 'Telegram (alert)',
                'body' => [
                    'text' => '🔴 SITE DOWN: {{ $item }} — Status: {{ $json.statusCode }}',
                ],
            ],
        ],
    ],

    // =============================================
    // W35: DAILY DIGEST (daily at 20:00)
    // Summary of all news activity
    // =============================================
    'W35_daily_digest' => [
        'name' => 'W35: Daily News Digest (20:00)',
        'trigger' => 'cron',
        'schedule' => '0 20 * * *',
        'nodes' => [
            ['type' => 'Schedule Trigger', 'params' => ['cronExpression' => '0 20 * * *']],
            [
                'type' => 'HTTP Request',
                'method' => 'GET',
                'url' => '{{ $API_URL }}/api/v1/news/statistics',
                'note' => 'Get today stats',
            ],
            [
                'type' => 'Function',
                'code' => <<<'JS'
const stats = $json.data;
const msg = `📰 <b>Daily News Digest</b>\n\n` +
  `📥 Parsed today: ${stats.today_parsed}\n` +
  `📤 Published today: ${stats.today_published}\n` +
  `📊 Avg plagiarism: ${stats.avg_plagiarism}%\n` +
  `📁 Total articles: ${stats.total_articles}\n\n` +
  `<b>By Site:</b>\n` +
  Object.entries(stats.by_site || {}).map(([s, c]) => `  • ${s}: ${c}`).join('\n');
return [{json: {message: msg}}];
JS,
            ],
            [
                'type' => 'Telegram',
                'body' => [
                    'chat_id' => '{{ $TELEGRAM_ADMIN_CHAT_ID }}',
                    'text' => '{{ $json.message }}',
                    'parse_mode' => 'HTML',
                ],
            ],
            [
                'type' => 'Send Email',
                'to' => 'wincasetop@gmail.com',
                'subject' => 'WinCase News Digest — {{ $today }}',
                'body' => '{{ $json.message }}',
            ],
        ],
    ],
];

// ---------------------------------------------------------------
// Аннотация (RU):
// 8 n8n Workflows (W28-W35) для полной автоматизации News Pipeline:
//
// W28: Critical Parser (5 мин) — UDSC, Gov.pl, PAP, SchengenVisaInfo → parse → rewrite → publish → Telegram
// W29: High Priority (10 мин) — Euronews, Politico, iGaming, Ukraine
// W30: Medium Priority (30 мин) — Tech, Finance, Sports, Culture
// W31: Low Priority (60 мин) — Lifestyle, Trends, Education
// W32: AI Rewrite Engine (10 мин) — batch 10 articles, 3-pass plagiarism
// W33: Auto Publisher (5 мин) — publish rewritten + clean articles to all target sites
// W34: Site Health Monitor (15 мин) — ping all 8 sites, alert if down
// W35: Daily Digest (20:00) — summary → Telegram + Email
//
// Цепочка: Parse (W28-31) → Rewrite (W32) → Publish (W33) → Monitor (W34) → Report (W35)
// CRITICAL news: Parse → Rewrite → Publish < 2 минуты end-to-end.
// Файл: n8n/news_workflows_v2.php
// ---------------------------------------------------------------
