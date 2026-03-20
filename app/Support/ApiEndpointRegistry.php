<?php

namespace App\Support;

class ApiEndpointRegistry
{
    public static function all(): array
    {
        return [
            'total_endpoints' => 212,
            'modules' => [
                // // ==========================================
        // AUTH & USERS (18 endpoints)
        // ==========================================
        'auth' => [
            'prefix' => '/auth',
            'endpoints' => [
                ['POST', '/login', 'Login with email+password+optional 2FA code', 'public'],
                ['POST', '/logout', 'Logout (revoke token)', 'auth'],
                ['GET', '/me', 'Get current user profile + permissions', 'auth'],
                ['POST', '/2fa/enable', 'Enable 2FA (get QR code)', 'auth'],
                ['POST', '/2fa/confirm', 'Confirm 2FA with TOTP code', 'auth'],
                ['POST', '/2fa/disable', 'Disable 2FA (requires password)', 'auth'],
                ['POST', '/password/forgot', 'Send password reset link', 'public'],
                ['POST', '/password/reset', 'Reset password with token', 'public'],
            ],
        ],
        'users' => [
            'prefix' => '/users',
            'endpoints' => [
                ['GET', '/', 'List users (filters: role, status, search)', 'boss'],
                ['GET', '/roles', 'Get available roles with abilities', 'boss'],
                ['GET', '/team-stats', 'Team statistics (by role, department)', 'boss'],
                ['GET', '/{id}', 'Get user profile + stats', 'boss'],
                ['POST', '/', 'Create user', 'boss'],
                ['PUT', '/{id}', 'Update user', 'boss'],
                ['POST', '/{id}/role', 'Change user role (force re-login)', 'boss'],
                ['POST', '/{id}/deactivate', 'Deactivate user', 'boss'],
                ['POST', '/{id}/activate', 'Activate user', 'boss'],
                ['DELETE', '/{id}', 'Soft-delete user', 'boss'],
            ],
        ],

        // ==========================================
        // LEADS (14 endpoints)
        // ==========================================
        'leads' => [
            'prefix' => '/leads',
            'endpoints' => [
                ['GET', '/', 'List leads (filters: status, source, service_type, search, date)', 'auth'],
                ['GET', '/sources', 'Get 14 acquisition sources', 'auth'],
                ['GET', '/funnel', 'Funnel analytics (conversion rates)', 'auth'],
                ['GET', '/unassigned', 'Unassigned leads list', 'auth'],
                ['GET', '/statistics', 'Lead statistics (by source, status, daily)', 'auth'],
                ['GET', '/{id}', 'Lead detail + timeline', 'auth'],
                ['POST', '/', 'Create lead (name, phone, source, service_type, language)', 'auth'],
                ['PUT', '/{id}', 'Update lead', 'auth'],
                ['PATCH', '/{id}', 'Update lead status', 'auth'],
                ['POST', '/{id}/assign', 'Assign lead to manager', 'auth'],
                ['POST', '/{id}/convert', 'Convert lead to client', 'auth'],
                ['POST', '/{id}/note', 'Add note to lead', 'auth'],
                ['POST', '/webhook/{source}', 'External webhook (Facebook, TikTok, website)', 'public'],
                ['DELETE', '/{id}', 'Delete lead', 'auth'],
            ],
        ],

        // ==========================================
        // CORE CRM (35 endpoints)
        // ==========================================
        'clients' => [
            'prefix' => '/clients',
            'endpoints' => [
                ['GET', '/', 'List clients (filters: status, nationality, search)', 'auth'],
                ['GET', '/{id}', 'Client detail + cases + docs + timeline', 'auth'],
                ['POST', '/', 'Create client', 'auth'],
                ['PUT', '/{id}', 'Update client', 'auth'],
                ['GET', '/{id}/cases', 'Client cases list', 'auth'],
                ['GET', '/{id}/documents', 'Client documents list', 'auth'],
                ['GET', '/{id}/timeline', 'Client activity timeline', 'auth'],
                ['DELETE', '/{id}', 'Archive client', 'auth'],
            ],
        ],
        'cases' => [
            'prefix' => '/cases',
            'endpoints' => [
                ['GET', '/', 'List cases (filters: status, service_type, assigned_to)', 'auth'],
                ['GET', '/{id}', 'Case detail + docs + tasks + notes', 'auth'],
                ['POST', '/', 'Create case', 'auth'],
                ['PUT', '/{id}', 'Update case', 'auth'],
                ['POST', '/{id}/status', 'Change case status (10-step workflow)', 'auth'],
                ['POST', '/{id}/assign', 'Assign case to manager', 'auth'],
                ['POST', '/{id}/note', 'Add note to case', 'auth'],
                ['GET', '/{id}/documents', 'Case documents', 'auth'],
                ['POST', '/{id}/document', 'Upload document to case', 'auth'],
                ['GET', '/{id}/tasks', 'Case tasks', 'auth'],
                ['GET', '/{id}/invoices', 'Case invoices', 'auth'],
                ['DELETE', '/{id}', 'Close case', 'auth'],
            ],
        ],
        'tasks' => [
            'prefix' => '/tasks',
            'endpoints' => [
                ['GET', '/', 'List tasks (filters: status, priority, assigned_to, overdue)', 'auth'],
                ['GET', '/my', 'Current user tasks', 'auth'],
                ['GET', '/{id}', 'Task detail', 'auth'],
                ['POST', '/', 'Create task', 'auth'],
                ['PUT', '/{id}', 'Update task', 'auth'],
                ['POST', '/{id}/status', 'Change task status', 'auth'],
                ['POST', '/{id}/assign', 'Assign task', 'auth'],
                ['DELETE', '/{id}', 'Delete task', 'auth'],
            ],
        ],
        'documents' => [
            'prefix' => '/documents',
            'endpoints' => [
                ['GET', '/', 'List documents (17 types)', 'auth'],
                ['GET', '/expiring', 'Documents expiring in N days', 'auth'],
                ['GET', '/{id}', 'Document detail', 'auth'],
                ['POST', '/', 'Upload document (max 20MB)', 'auth'],
                ['PUT', '/{id}', 'Update document metadata', 'auth'],
                ['GET', '/{id}/download', 'Download (temporary signed URL)', 'auth'],
                ['DELETE', '/{id}', 'Delete document', 'auth'],
            ],
        ],
        'calendar' => [
            'prefix' => '/calendar',
            'endpoints' => [
                ['GET', '/', 'List events (7 types, date range)', 'auth'],
                ['GET', '/upcoming', 'Upcoming events (next 7 days)', 'auth'],
                ['GET', '/{id}', 'Event detail', 'auth'],
                ['POST', '/', 'Create event', 'auth'],
                ['PUT', '/{id}', 'Update event', 'auth'],
                ['POST', '/{id}/cancel', 'Cancel event', 'auth'],
                ['DELETE', '/{id}', 'Delete event', 'auth'],
            ],
        ],

        // ==========================================
        // POS & ACCOUNTING (27 endpoints)
        // ==========================================
        'pos' => [
            'prefix' => '/pos',
            'endpoints' => [
                ['GET', '/', 'List transactions (status, method)', 'auth'],
                ['GET', '/pending', 'Pending approvals', 'auth'],
                ['GET', '/statistics', 'POS stats (daily, by method)', 'auth'],
                ['GET', '/{id}', 'Transaction detail', 'auth'],
                ['POST', '/receive', 'Receive payment', 'auth'],
                ['POST', '/{id}/approve', 'Approve payment', 'auth'],
                ['POST', '/{id}/reject', 'Reject payment', 'auth'],
                ['POST', '/{id}/refund', 'Refund payment', 'auth'],
                ['GET', '/receipt/{id}', 'Generate receipt PDF', 'auth'],
                ['GET', '/report/daily', 'Daily POS report', 'auth'],
                ['GET', '/report/monthly', 'Monthly POS report', 'auth'],
            ],
        ],
        'accounting' => [
            'prefix' => '/accounting',
            'endpoints' => [
                ['GET', '/invoices', 'List invoices', 'boss'],
                ['POST', '/invoices', 'Create invoice', 'boss'],
                ['GET', '/invoices/{id}', 'Invoice detail', 'boss'],
                ['PUT', '/invoices/{id}', 'Update invoice', 'boss'],
                ['GET', '/invoices/{id}/pdf', 'Generate invoice PDF', 'boss'],
                ['GET', '/tax/pit', 'PIT calculation', 'boss'],
                ['GET', '/tax/cit', 'CIT calculation', 'boss'],
                ['GET', '/tax/vat', 'VAT declaration', 'boss'],
                ['GET', '/tax/zus', 'ZUS contributions', 'boss'],
                ['GET', '/tax/calendar', 'Tax calendar (deadlines)', 'boss'],
                ['GET', '/reports/pnl', 'Profit & Loss', 'boss'],
                ['GET', '/reports/balance', 'Balance sheet', 'boss'],
                ['GET', '/reports/cashflow', 'Cash flow', 'boss'],
                ['POST', '/export/jpk', 'Export JPK_VAT', 'boss'],
                ['POST', '/export/pit', 'Export PIT declaration', 'boss'],
                ['GET', '/statistics', 'Accounting stats', 'boss'],
            ],
        ],

        // ==========================================
        // MARKETING (45 endpoints)
        // ==========================================
        'ads' => [
            'prefix' => '/ads',
            'endpoints' => [
                ['GET', '/campaigns', 'List campaigns (5 platforms)', 'auth'],
                ['GET', '/campaigns/{id}', 'Campaign detail', 'auth'],
                ['POST', '/campaigns', 'Create campaign', 'auth'],
                ['PUT', '/campaigns/{id}', 'Update campaign', 'auth'],
                ['GET', '/analytics', 'Cross-platform analytics', 'auth'],
                ['GET', '/analytics/{platform}', 'Platform-specific analytics', 'auth'],
                ['POST', '/sync/{platform}', 'Sync platform data', 'auth'],
                ['GET', '/budget', 'Budget overview', 'auth'],
                ['PUT', '/budget/{platform}', 'Update platform budget', 'auth'],
                ['POST', '/conversions/offline', 'Upload offline conversions', 'auth'],
                ['GET', '/roi', 'ROI analysis', 'auth'],
                ['GET', '/audiences', 'Audience segments', 'auth'],
                ['POST', '/audiences/sync', 'Sync audiences to platforms', 'auth'],
            ],
        ],
        'seo' => [
            'prefix' => '/seo',
            'endpoints' => [
                ['GET', '/overview', 'SEO overview (4 domains, 8 satellites)', 'auth'],
                ['GET', '/gsc', 'Google Search Console data', 'auth'],
                ['GET', '/ga4', 'Google Analytics 4 data', 'auth'],
                ['GET', '/ahrefs', 'Ahrefs backlink data', 'auth'],
                ['GET', '/keywords', 'Keyword tracking', 'auth'],
                ['GET', '/satellites', 'Satellite sites status', 'auth'],
                ['POST', '/audit', 'Run SEO audit', 'auth'],
                ['GET', '/competitors', 'Competitor analysis', 'auth'],
                ['POST', '/sitemap/generate', 'Generate sitemap', 'auth'],
                ['GET', '/statistics', 'SEO statistics', 'auth'],
            ],
        ],
        'social' => [
            'prefix' => '/social',
            'endpoints' => [
                ['GET', '/overview', 'All 8 platforms overview', 'auth'],
                ['GET', '/platforms/{platform}', 'Platform detail', 'auth'],
                ['POST', '/post', 'Create unified post', 'auth'],
                ['POST', '/post/schedule', 'Schedule post', 'auth'],
                ['GET', '/posts', 'List posts (all platforms)', 'auth'],
                ['GET', '/posts/{id}', 'Post detail + analytics', 'auth'],
                ['PUT', '/posts/{id}', 'Update post', 'auth'],
                ['DELETE', '/posts/{id}', 'Delete post', 'auth'],
                ['GET', '/calendar', 'Content calendar', 'auth'],
                ['GET', '/inbox', 'Unified inbox (all platforms)', 'auth'],
                ['POST', '/inbox/{id}/reply', 'Reply to message', 'auth'],
                ['GET', '/analytics', 'Cross-platform analytics', 'auth'],
                ['GET', '/analytics/{platform}', 'Platform analytics', 'auth'],
                ['POST', '/sync/{platform}', 'Sync platform data', 'auth'],
                ['GET', '/statistics', 'Social statistics', 'auth'],
            ],
        ],
        'brand' => [
            'prefix' => '/brand',
            'endpoints' => [
                ['GET', '/directories', '54 directories overview', 'auth'],
                ['GET', '/directories/{id}', 'Directory detail', 'auth'],
                ['PUT', '/directories/{id}', 'Update directory listing', 'auth'],
                ['POST', '/nap-check', 'Run NAP consistency check', 'auth'],
                ['GET', '/reviews', 'Reviews hub (4 platforms)', 'auth'],
                ['POST', '/reviews/{id}/reply', 'Reply to review', 'auth'],
                ['GET', '/reputation-score', 'Overall reputation score', 'auth'],
                ['GET', '/statistics', 'Brand statistics', 'auth'],
            ],
        ],
        'landings' => [
            'prefix' => '/landings',
            'endpoints' => [
                ['GET', '/', 'List landing pages (~64 pages, 4 domains)', 'auth'],
                ['GET', '/{id}', 'Landing page detail + analytics', 'auth'],
                ['POST', '/', 'Create landing page', 'auth'],
                ['PUT', '/{id}', 'Update landing page', 'auth'],
                ['POST', '/{id}/ab-test', 'Create A/B test variant', 'auth'],
                ['GET', '/analytics', 'Landing pages analytics', 'auth'],
                ['POST', '/track', 'Track conversion (public)', 'public'],
            ],
        ],

        // ==========================================
        // DASHBOARD & NEWS (22 endpoints)
        // ==========================================
        'dashboard' => [
            'prefix' => '/dashboard',
            'endpoints' => [
                ['GET', '/', 'Full dashboard (all sections)', 'auth'],
                ['GET', '/kpi', 'KPI cards only', 'auth'],
                ['GET', '/leads', 'Leads section', 'auth'],
                ['GET', '/finance', 'Finance section', 'auth'],
                ['GET', '/ads', 'Ads section', 'auth'],
                ['GET', '/social', 'Social section', 'auth'],
                ['GET', '/seo', 'SEO section', 'auth'],
                ['GET', '/realtime', 'Real-time data (WebSocket)', 'auth'],
            ],
        ],
        'news' => [
            'prefix' => '/news',
            'endpoints' => [
                ['GET', '/sources', 'All 27 news sources + mappings', 'auth'],
                ['GET', '/articles', 'List articles (filters: status, category, source, priority)', 'auth'],
                ['GET', '/articles/{id}', 'Article detail + translations + publish logs', 'auth'],
                ['POST', '/parse', 'Trigger parsing (optional: priority filter)', 'auth'],
                ['POST', '/parse/{sourceKey}', 'Parse single source', 'auth'],
                ['POST', '/rewrite/{id}', 'AI rewrite article', 'auth'],
                ['POST', '/rewrite-batch', 'Batch rewrite (10 articles)', 'auth'],
                ['POST', '/translate/{id}', 'Translate to target language', 'auth'],
                ['POST', '/publish', 'Auto-publish all ready articles', 'auth'],
                ['POST', '/articles/{id}/approve', 'Approve for publishing', 'auth'],
                ['POST', '/articles/{id}/reject', 'Reject article', 'auth'],
                ['GET', '/statistics', 'News pipeline stats', 'auth'],
                ['GET', '/schedule', 'Publish schedule config', 'auth'],
                ['GET', '/feed', 'Live feed history', 'auth'],
            ],
        ],

        // ==========================================
        // SYSTEM (29 endpoints)
        // ==========================================
        'notifications' => [
            'prefix' => '/notifications',
            'endpoints' => [
                ['GET', '/', 'User notifications (20 latest)', 'auth'],
                ['GET', '/unread-count', 'Unread count', 'auth'],
                ['POST', '/read', 'Mark all as read', 'auth'],
                ['POST', '/{id}/read', 'Mark single as read', 'auth'],
                ['GET', '/templates', '12 notification templates', 'auth'],
                ['POST', '/send', 'Send custom notification (boss)', 'boss'],
                ['POST', '/send-bulk', 'Bulk send to users (boss)', 'boss'],
            ],
        ],
        'reports' => [
            'prefix' => '/reports',
            'endpoints' => [
                ['GET', '/types', '8 report types', 'auth'],
                ['POST', '/generate', 'Generate report (PDF/XLSX/JSON)', 'auth'],
                ['GET', '/download', 'Download generated file', 'auth'],
                ['GET', '/history', 'Generated reports history', 'auth'],
                ['GET', '/quick/{type}', 'Quick JSON data', 'auth'],
                ['GET', '/scheduled', 'List scheduled reports', 'auth'],
                ['POST', '/scheduled', 'Create scheduled report', 'auth'],
                ['DELETE', '/scheduled/{id}', 'Delete scheduled report', 'auth'],
            ],
        ],
        'audit' => [
            'prefix' => '/audit',
            'endpoints' => [
                ['GET', '/logs', 'Paginated audit logs (8 filters)', 'boss'],
                ['GET', '/security', 'Security report (failed logins, by IP)', 'boss'],
                ['GET', '/stats', 'Activity statistics (30d)', 'boss'],
                ['GET', '/actions', 'Available action types', 'boss'],
                ['GET', '/entities', 'Available entity types', 'boss'],
                ['GET', '/timeline/{entityType}/{entityId}', 'Entity timeline', 'boss'],
                ['GET', '/user/{userId}', 'User activity log', 'boss'],
            ],
        ],
        'system' => [
            'prefix' => '/system',
            'endpoints' => [
                ['GET', '/health', 'Full health check (7 services)', 'boss'],
                ['GET', '/settings', 'System settings + integrations', 'boss'],
                ['POST', '/cache/clear', 'Clear cache (type)', 'boss'],
                ['POST', '/cache/optimize', 'Optimize all caches', 'boss'],
                ['GET', '/maintenance/status', 'Maintenance mode status', 'boss'],
                ['POST', '/maintenance/enable', 'Enable maintenance mode', 'boss'],
                ['POST', '/maintenance/disable', 'Disable maintenance mode', 'boss'],
            ],
        ],
        'n8n' => [
            'prefix' => '/n8n',
            'endpoints' => [
                ['GET', '/workflows', 'List 27 n8n workflows', 'boss'],
                ['GET', '/health', 'n8n health check', 'boss'],
            ],
        ],
    ],

    // =====================================================
    // SUMMARY
    // =====================================================
    'total_endpoints' => 212,
    'auth_levels' => [
        'public' => 'No authentication required (login, webhooks)',
        'auth' => 'Bearer token required (auth:sanctum)',
        'boss' => 'Boss role required (ability:*)',
        'boss' => 'Accountant role (accounting abilities)',
    ],
    'response_format' => [
        'success' => '{"success": true, "data": {...}}',
        'error' => '{"success": false, "message": "...", "errors": {...}}',
        'paginated' => '{"success": true, "data": {"data": [...], "meta": {"total", "per_page", "current_page", "last_page"}}}',
    ],
];

// ---------------------------------------------------------------
// Аннотация (RU):
// OpenAPI 3.1 спецификация — полная документация всех 212 endpoints.
// 22 модуля: Auth (8), Users (10), Leads (14), Clients (8), Cases (12),
// Tasks (8), Documents (7), Calendar (7), POS (11), Accounting (16),
// Ads (13), SEO (10), Social (15), Brand (8), Landings (7), Dashboard (8),
// News (14), Notifications (7), Reports (8), Audit (7), System (7), n8n (2).
// Swagger UI: https://api.wincase.pro/api/documentation
// 4 auth levels: public, auth (sanctum), admin (ability:*), accountant.
// Файл: config/openapi.php + app/Http/Controllers/Api/ApiDocumentation.php
// ---------------------------------------------------------------


            ],
        ];
    }
}
