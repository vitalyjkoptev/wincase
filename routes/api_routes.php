<?php

// =====================================================
// FILE: routes/api.php — MASTER ROUTER
// All 212+ endpoints consolidated
// =====================================================

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{
    AuthController,
    SettingsController,
    UsersController,
    LeadsController,
    ClientsController,
    CasesController,
    TasksController,
    DocumentsController,
    CalendarController,
    PosController,
    AccountingController,
    AdsController,
    SeoController,
    SocialController,
    DashboardController,
    BrandController,
    LandingsController,
    NewsController,
    NotificationController,
    ReportController,
    AuditController,
    SystemController,
    PaymentGatewayController,
    AnalyticsController,
    ClientPortalController,
    VerificationController,
    JobsPortalController,
    N8nAutomationController,
};

// =====================================================
// RATE LIMITING
// =====================================================

// AUTH — public (rate limited: 10 per minute per IP)
Route::prefix('v1/auth')->middleware('throttle:auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::post('/register-client', [ClientPortalController::class, 'registerClient']);
});

// PUBLIC WEBHOOKS
Route::prefix('v1')->group(function () {
    Route::post('/leads/webhook/{source}', [LeadsController::class, 'webhook']);
    Route::post('/landings/track', [LandingsController::class, 'track']);

    // Payment gateway webhooks (NO auth — called by Stripe/P24/PayPal servers)
    Route::prefix('webhooks')->group(function () {
        Route::post('/stripe', [PaymentGatewayController::class, 'webhookStripe']);
        Route::post('/przelewy24', [PaymentGatewayController::class, 'webhookP24']);
        Route::post('/paypal', [PaymentGatewayController::class, 'webhookPayPal']);
    });

    // PayPal return URL (capture after user approves)
    Route::get('/payments/paypal/capture', [PaymentGatewayController::class, 'paypalCapture']);

    // Authologic verification callback (NO auth — called by Authologic servers)
    Route::post('/verification/callback', [VerificationController::class, 'callback']);
    Route::get('/verification/callback', [VerificationController::class, 'callback']);

    // Available gateways (public — for client portal)
    Route::get('/payments/gateways', [PaymentGatewayController::class, 'gateways']);
});

// =====================================================
// AUTHENTICATED ROUTES
// =====================================================

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

    // ==========================================
    // AUTH (authenticated actions)
    // ==========================================
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/2fa/enable', [AuthController::class, 'enable2FA']);
        Route::post('/2fa/confirm', [AuthController::class, 'confirm2FA']);
        Route::post('/2fa/disable', [AuthController::class, 'disable2FA']);
        Route::post('/password/change', [AuthController::class, 'changePassword']);
    });

    // ==========================================
    // DASHBOARD (boss + staff only)
    // ==========================================
    Route::prefix('dashboard')->middleware('role:boss,staff')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/kpi', [DashboardController::class, 'kpi']);
        Route::get('/cases', [DashboardController::class, 'cases']);
        Route::get('/leads', [DashboardController::class, 'leads']);
        Route::get('/finance', [DashboardController::class, 'finance']);
        Route::get('/ads', [DashboardController::class, 'ads']);
        Route::get('/social', [DashboardController::class, 'social']);
        Route::get('/seo', [DashboardController::class, 'seo']);
        Route::get('/accounting', [DashboardController::class, 'accounting']);
        Route::get('/automation', [DashboardController::class, 'automation']);
        Route::get('/realtime', [DashboardController::class, 'realtime']);
    });

    // ==========================================
    // LEADS (14 endpoints — boss + staff)
    // ==========================================
    Route::prefix('leads')->middleware('role:boss,staff')->group(function () {
        Route::get('/', [LeadsController::class, 'index']);
        Route::get('/sources', [LeadsController::class, 'sources']);
        Route::get('/funnel', [LeadsController::class, 'funnel']);
        Route::get('/unassigned', [LeadsController::class, 'unassigned']);
        Route::get('/statistics', [LeadsController::class, 'statistics']);
        Route::get('/{id}', [LeadsController::class, 'show']);
        Route::post('/', [LeadsController::class, 'store'])->middleware('ability:leads.create');
        Route::put('/{id}', [LeadsController::class, 'update'])->middleware('ability:leads.edit');
        Route::patch('/{id}', [LeadsController::class, 'updateStatus'])->middleware('ability:leads.edit');
        Route::post('/{id}/assign', [LeadsController::class, 'assign'])->middleware('ability:leads.assign');
        Route::post('/{id}/convert', [LeadsController::class, 'convert'])->middleware('ability:leads.convert');
        Route::post('/{id}/note', [LeadsController::class, 'addNote']);
        Route::delete('/{id}', [LeadsController::class, 'destroy'])->middleware('ability:leads.delete');
    });

    // ==========================================
    // CLIENTS (8 endpoints — boss + staff)
    // ==========================================
    Route::prefix('clients')->middleware('role:boss,staff')->group(function () {
        Route::get('/', [ClientsController::class, 'index']);
        Route::get('/{id}', [ClientsController::class, 'show']);
        Route::post('/', [ClientsController::class, 'store'])->middleware('ability:clients.create');
        Route::put('/{id}', [ClientsController::class, 'update'])->middleware('ability:clients.edit');
        Route::get('/{id}/cases', [ClientsController::class, 'cases']);
        Route::get('/{id}/documents', [ClientsController::class, 'documents']);
        Route::get('/{id}/timeline', [ClientsController::class, 'timeline']);
        Route::delete('/{id}', [ClientsController::class, 'destroy'])->middleware('ability:clients.delete');
    });

    // ==========================================
    // CASES (12 endpoints — boss + staff)
    // ==========================================
    Route::prefix('cases')->middleware('role:boss,staff')->group(function () {
        Route::get('/', [CasesController::class, 'index']);
        Route::get('/deadlines', [N8nAutomationController::class, 'caseDeadlines']);
        Route::get('/{id}', [CasesController::class, 'show']);
        Route::post('/', [CasesController::class, 'store'])->middleware('ability:cases.create');
        Route::put('/{id}', [CasesController::class, 'update'])->middleware('ability:cases.edit');
        Route::post('/{id}/status', [CasesController::class, 'changeStatus'])->middleware('ability:cases.edit');
        Route::post('/{id}/assign', [CasesController::class, 'assign'])->middleware('ability:cases.assign');
        Route::post('/{id}/note', [CasesController::class, 'addNote']);
        Route::get('/{id}/documents', [CasesController::class, 'documents']);
        Route::post('/{id}/document', [CasesController::class, 'uploadDocument']);
        Route::get('/{id}/tasks', [CasesController::class, 'tasks']);
        Route::get('/{id}/invoices', [CasesController::class, 'invoices']);
        Route::delete('/{id}', [CasesController::class, 'destroy'])->middleware('ability:cases.delete');
    });

    // ==========================================
    // TASKS (8 endpoints — boss + staff)
    // ==========================================
    Route::prefix('tasks')->middleware('role:boss,staff')->group(function () {
        Route::get('/', [TasksController::class, 'index']);
        Route::get('/my', [TasksController::class, 'my']);
        Route::get('/{id}', [TasksController::class, 'show']);
        Route::post('/', [TasksController::class, 'store'])->middleware('ability:tasks.create');
        Route::put('/{id}', [TasksController::class, 'update'])->middleware('ability:tasks.edit');
        Route::post('/{id}/status', [TasksController::class, 'changeStatus']);
        Route::post('/{id}/assign', [TasksController::class, 'assign'])->middleware('ability:tasks.assign');
        Route::delete('/{id}', [TasksController::class, 'destroy'])->middleware('ability:tasks.delete');
    });

    // ==========================================
    // DOCUMENTS (7 endpoints — boss + staff)
    // ==========================================
    Route::prefix('documents')->middleware('role:boss,staff')->group(function () {
        Route::get('/', [DocumentsController::class, 'index']);
        Route::get('/expiring', [DocumentsController::class, 'expiring']);
        Route::get('/admin-vault', [DocumentsController::class, 'adminVault']);
        Route::get('/{id}', [DocumentsController::class, 'show']);
        Route::post('/', [DocumentsController::class, 'store'])->middleware('ability:documents.upload');
        Route::put('/{id}', [DocumentsController::class, 'update']);
        Route::get('/{id}/download', [DocumentsController::class, 'download']);
        Route::get('/{id}/preview', [DocumentsController::class, 'preview']);
        Route::put('/{id}/status', [DocumentsController::class, 'changeStatus']);
        Route::post('/{id}/status', [DocumentsController::class, 'changeStatus']);
        Route::delete('/{id}', [DocumentsController::class, 'destroy'])->middleware('ability:documents.delete');
    });

    // ==========================================
    // CALENDAR (7 endpoints — boss + staff)
    // ==========================================
    Route::prefix('calendar')->middleware('role:boss,staff')->group(function () {
        Route::get('/', [CalendarController::class, 'index']);
        Route::get('/upcoming', [CalendarController::class, 'upcoming']);
        Route::get('/{id}', [CalendarController::class, 'show']);
        Route::post('/', [CalendarController::class, 'store']);
        Route::put('/{id}', [CalendarController::class, 'update']);
        Route::post('/{id}/cancel', [CalendarController::class, 'cancel']);
        Route::delete('/{id}', [CalendarController::class, 'destroy']);
    });

    // ==========================================
    // POS (11 endpoints — boss + staff)
    // ==========================================
    Route::prefix('pos')->middleware('role:boss,staff')->group(function () {
        Route::get('/', [PosController::class, 'index']);
        Route::get('/pending', [PosController::class, 'pending']);
        Route::get('/statistics', [PosController::class, 'statistics']);
        Route::get('/{id}', [PosController::class, 'show']);
        Route::post('/receive', [PosController::class, 'receive']);
        Route::post('/{id}/approve', [PosController::class, 'approve'])->middleware('ability:pos.approve');
        Route::post('/{id}/reject', [PosController::class, 'reject'])->middleware('ability:pos.approve');
        Route::post('/{id}/refund', [PosController::class, 'refund'])->middleware('ability:pos.refund');
        Route::get('/receipt/{id}', [PosController::class, 'receipt']);
        Route::get('/report/daily', [PosController::class, 'dailyReport']);
        Route::get('/report/monthly', [PosController::class, 'monthlyReport']);
    });

    // ==========================================
    // PAYMENTS (list + manual + online gateways — boss + staff)
    // ==========================================
    Route::prefix('payments')->middleware('role:boss,staff')->group(function () {
        Route::get('/', [PaymentGatewayController::class, 'index']);
        Route::post('/', [PaymentGatewayController::class, 'store']);
        Route::post('/checkout', [PaymentGatewayController::class, 'checkout']);
        Route::get('/{id}/status', [PaymentGatewayController::class, 'status']);
        Route::post('/{id}/refund', [PaymentGatewayController::class, 'refund'])->middleware('ability:pos.approve');
        Route::get('/by-invoice/{invoiceId}', [PaymentGatewayController::class, 'byInvoice']);
    });

    // ==========================================
    // ACCOUNTING (16 endpoints — accountant+admin)
    // ==========================================
    Route::prefix('accounting')->middleware(['role:boss,staff', 'ability:accounting.view'])->group(function () {
        Route::get('/invoices', [AccountingController::class, 'invoices']);
        Route::post('/invoices', [AccountingController::class, 'createInvoice']);
        Route::get('/invoices/{id}', [AccountingController::class, 'showInvoice']);
        Route::put('/invoices/{id}', [AccountingController::class, 'updateInvoice']);
        Route::get('/invoices/{id}/pdf', [AccountingController::class, 'invoicePdf']);
        Route::get('/tax/pit', [AccountingController::class, 'pit']);
        Route::get('/tax/cit', [AccountingController::class, 'cit']);
        Route::get('/tax/vat', [AccountingController::class, 'vat']);
        Route::get('/tax/zus', [AccountingController::class, 'zus']);
        Route::get('/tax/calendar', [AccountingController::class, 'taxCalendar']);
        Route::get('/reports/pnl', [AccountingController::class, 'pnl']);
        Route::get('/reports/balance', [AccountingController::class, 'balance']);
        Route::get('/reports/cashflow', [AccountingController::class, 'cashflow']);
        Route::post('/export/jpk', [AccountingController::class, 'exportJPK']);
        Route::post('/export/pit', [AccountingController::class, 'exportPIT']);
        Route::get('/statistics', [AccountingController::class, 'statistics']);

        // Expenses
        Route::get('/expenses', [AccountingController::class, 'listExpenses']);
        Route::post('/expenses', [AccountingController::class, 'storeExpense']);
        Route::delete('/expenses/{expense}', [AccountingController::class, 'destroyExpense']);
    });

    // ==========================================
    // MARKETING: ADS + SEO + SOCIAL + BRAND + LANDINGS
    // ==========================================
    Route::prefix('ads')->middleware('role:boss,staff')->group(function () {
        Route::get('/overview', [AdsController::class, 'overview']);
        Route::get('/budget', [AdsController::class, 'budget']);
        Route::post('/sync', [AdsController::class, 'sync'])->middleware('ability:ads.manage');
        Route::get('/{platform}', [AdsController::class, 'platform']);
        Route::get('/{platform}/campaigns', [AdsController::class, 'campaigns']);
    });

    Route::prefix('seo')->middleware('role:boss,staff')->group(function () {
        Route::get('/overview', [SeoController::class, 'overview']);
        Route::get('/keywords', [SeoController::class, 'keywords']);
        Route::get('/network', [SeoController::class, 'network']);
        Route::get('/backlinks', [SeoController::class, 'backlinks']);
        Route::get('/reviews', [SeoController::class, 'reviews']);
        Route::get('/brand', [SeoController::class, 'brand']);
    });

    Route::prefix('social')->middleware('role:boss,staff')->group(function () {
        Route::get('/accounts', [SocialController::class, 'accounts']);
        Route::post('/publish', [SocialController::class, 'publish'])->middleware('ability:social.post');
        Route::get('/posts', [SocialController::class, 'posts']);
        Route::get('/posts/{id}/analytics', [SocialController::class, 'postAnalytics']);
        Route::get('/calendar', [SocialController::class, 'calendar']);
        Route::get('/inbox', [SocialController::class, 'inbox']);
        Route::post('/sync', [SocialController::class, 'sync']);
    });

    Route::prefix('brand')->middleware('role:boss,staff')->group(function () {
        Route::get('/overview', [BrandController::class, 'overview']);
        Route::get('/listings', [BrandController::class, 'listings']);
        Route::post('/nap-check', [BrandController::class, 'napCheck']);
        Route::get('/reviews', [BrandController::class, 'reviewsStats']);
        Route::get('/reviews/list', [BrandController::class, 'reviewsList']);
        Route::post('/reviews/{id}/reply', [BrandController::class, 'replyToReview']);
        Route::post('/reviews/sync', [BrandController::class, 'syncReviews']);
    });

    Route::prefix('landings')->middleware('role:boss,staff')->group(function () {
        Route::get('/', [LandingsController::class, 'index']);
        Route::get('/analytics', [LandingsController::class, 'analytics']);
        Route::post('/', [LandingsController::class, 'store'])->middleware('ability:landings.manage');
        Route::get('/{id}', [LandingsController::class, 'show']);
        Route::put('/{id}', [LandingsController::class, 'update'])->middleware('ability:landings.manage');
        Route::post('/{id}/variants', [LandingsController::class, 'createVariant'])->middleware('ability:landings.manage');
    });

    // ==========================================
    // ANALYTICS (4 endpoints)
    // ==========================================
    Route::prefix('analytics')->middleware('role:boss,staff')->group(function () {
        Route::get('/sales', [AnalyticsController::class, 'sales']);
        Route::get('/traffic', [AnalyticsController::class, 'traffic']);
        Route::get('/performance', [AnalyticsController::class, 'performance']);
        Route::get('/quota', [AnalyticsController::class, 'quota']);
    });

    // ==========================================
    // NEWS / CONTENT (12 endpoints)
    // ==========================================
    Route::prefix('news')->middleware('role:boss,staff')->group(function () {
        Route::get('/sources', [NewsController::class, 'sources']);
        Route::get('/articles', [NewsController::class, 'articles']);
        Route::get('/statistics', [NewsController::class, 'statistics']);
        Route::get('/categories', [NewsController::class, 'categories']);
        Route::get('/schedule', [NewsController::class, 'schedule']);
        Route::get('/feed', [NewsController::class, 'feedHistory']);
        Route::post('/articles', [NewsController::class, 'storeArticle'])->middleware('ability:news.manage');
        Route::get('/articles/{id}', [NewsController::class, 'showArticle']);
        Route::put('/articles/{id}', [NewsController::class, 'updateArticle'])->middleware('ability:news.manage');
        Route::delete('/articles/{id}', [NewsController::class, 'deleteArticle'])->middleware('ability:news.manage');
        Route::post('/articles/{id}/approve', [NewsController::class, 'approveArticle'])->middleware('ability:news.manage');
        Route::post('/articles/{id}/reject', [NewsController::class, 'rejectArticle'])->middleware('ability:news.manage');
    });

    // ==========================================
    // VERIFICATION / IDENTITY (9 endpoints — boss + staff)
    // ==========================================
    Route::prefix('verification')->middleware('role:boss,staff')->group(function () {
        Route::get('/', [VerificationController::class, 'index']);
        Route::get('/stats', [VerificationController::class, 'stats']);
        Route::get('/strategies', [VerificationController::class, 'strategies']);
        Route::get('/client/{clientId}', [VerificationController::class, 'clientHistory']);
        Route::get('/{id}', [VerificationController::class, 'status']);
        Route::post('/start/{clientId}', [VerificationController::class, 'start']);
        Route::post('/{id}/manual', [VerificationController::class, 'manualVerify'])->middleware('role:boss');
    });

    // ==========================================
    // CLIENT PORTAL (10 endpoints)
    // ==========================================
    Route::prefix('client-portal')->group(function () {
        Route::get('/dashboard', [ClientPortalController::class, 'dashboard']);
        Route::get('/profile', [ClientPortalController::class, 'profile']);
        Route::put('/profile', [ClientPortalController::class, 'updateProfile']);
        Route::post('/password', [ClientPortalController::class, 'changePassword']);
        Route::get('/cases', [ClientPortalController::class, 'cases']);
        Route::get('/documents', [ClientPortalController::class, 'documents']);
        Route::post('/documents', [ClientPortalController::class, 'uploadDocument']);
        Route::get('/messages', [ClientPortalController::class, 'messages']);
        Route::post('/messages', [ClientPortalController::class, 'sendMessage']);
        Route::get('/payments', [ClientPortalController::class, 'payments']);
    });

    // ==========================================
    // NOTIFICATIONS (7 endpoints)
    // ==========================================
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
        Route::post('/read', [NotificationController::class, 'markAllRead']);
        Route::post('/{id}/read', [NotificationController::class, 'markRead']);
        Route::post('/register-device', [NotificationController::class, 'registerDevice']);
        Route::get('/templates', [NotificationController::class, 'templates']);
        Route::post('/send', [NotificationController::class, 'send'])->middleware('ability:*');
        Route::post('/send-bulk', [NotificationController::class, 'sendBulk'])->middleware('ability:*');
    });

    // ==========================================
    // REPORTS (8 endpoints)
    // ==========================================
    Route::prefix('reports')->middleware('role:boss,staff')->group(function () {
        Route::get('/types', [ReportController::class, 'types']);
        Route::post('/generate', [ReportController::class, 'generate']);
        Route::get('/download', [ReportController::class, 'download']);
        Route::get('/history', [ReportController::class, 'history']);
        Route::get('/quick/{type}', [ReportController::class, 'quick']);
        Route::get('/scheduled', [ReportController::class, 'scheduledList']);
        Route::post('/scheduled', [ReportController::class, 'scheduledCreate']);
        Route::delete('/scheduled/{id}', [ReportController::class, 'scheduledDelete']);
    });

    // ==========================================
    // JOBS PORTAL (WinCaseJobs)
    // ==========================================
    Route::prefix('jobs')->middleware('role:boss')->group(function () {
        Route::get('/dashboard', [JobsPortalController::class, 'dashboard']);

        Route::get('/vacancies', [JobsPortalController::class, 'vacancies']);
        Route::get('/vacancies/{id}', [JobsPortalController::class, 'vacancyShow']);
        Route::post('/vacancies', [JobsPortalController::class, 'vacancyStore']);
        Route::put('/vacancies/{id}', [JobsPortalController::class, 'vacancyUpdate']);
        Route::delete('/vacancies/{id}', [JobsPortalController::class, 'vacancyDestroy']);

        Route::get('/employers', [JobsPortalController::class, 'employers']);
        Route::get('/employers/{id}', [JobsPortalController::class, 'employerShow']);
        Route::put('/employers/{id}', [JobsPortalController::class, 'employerUpdate']);

        Route::get('/seekers', [JobsPortalController::class, 'seekers']);
        Route::get('/seekers/{id}', [JobsPortalController::class, 'seekerShow']);
        Route::put('/seekers/{id}', [JobsPortalController::class, 'seekerUpdate']);

        Route::get('/parsed', [JobsPortalController::class, 'parsedJobs']);
        Route::post('/parsed/{id}/approve', [JobsPortalController::class, 'parsedJobApprove']);
        Route::post('/parsed/{id}/reject', [JobsPortalController::class, 'parsedJobReject']);

        // Parser sources management
        Route::get('/parser/sources', [JobsPortalController::class, 'parserSources']);
        Route::get('/parser/sources/{id}', [JobsPortalController::class, 'parserSourceShow']);
        Route::post('/parser/sources', [JobsPortalController::class, 'parserSourceStore']);
        Route::put('/parser/sources/{id}', [JobsPortalController::class, 'parserSourceUpdate']);
        Route::delete('/parser/sources/{id}', [JobsPortalController::class, 'parserSourceDestroy']);
        Route::post('/parser/sources/{id}/toggle', [JobsPortalController::class, 'parserSourceToggle']);
        Route::post('/parser/run', [JobsPortalController::class, 'parserRun']);
    });

    // ==========================================
    // ADMIN ONLY: USERS + AUDIT + SYSTEM
    // ==========================================
    Route::middleware('ability:*')->group(function () {
        // Users (10 endpoints)
        Route::prefix('users')->group(function () {
            Route::get('/', [UsersController::class, 'index']);
            Route::get('/roles', [UsersController::class, 'roles']);
            Route::get('/team-stats', [UsersController::class, 'teamStats']);
            Route::get('/{id}', [UsersController::class, 'show']);
            Route::post('/', [UsersController::class, 'store']);
            Route::put('/{id}', [UsersController::class, 'update']);
            Route::post('/{id}/role', [UsersController::class, 'changeRole']);
            Route::post('/{id}/password', [UsersController::class, 'changePassword']);
            Route::post('/{id}/deactivate', [UsersController::class, 'deactivate']);
            Route::post('/{id}/activate', [UsersController::class, 'activate']);
            Route::delete('/{id}', [UsersController::class, 'destroy']);
        });

        // Audit (7 endpoints)
        Route::prefix('audit')->group(function () {
            Route::get('/logs', [AuditController::class, 'logs']);
            Route::get('/security', [AuditController::class, 'security']);
            Route::get('/stats', [AuditController::class, 'stats']);
            Route::get('/actions', [AuditController::class, 'actions']);
            Route::get('/entities', [AuditController::class, 'entities']);
            Route::get('/timeline/{entityType}/{entityId}', [AuditController::class, 'timeline']);
            Route::get('/user/{userId}', [AuditController::class, 'userActivity']);
        });

        // System (7 endpoints)
        Route::prefix('system')->group(function () {
            Route::get('/health', [SystemController::class, 'health']);
            Route::get('/settings', [SystemController::class, 'settings']);
            Route::post('/cache/clear', [SystemController::class, 'clearCache']);
            Route::post('/cache/optimize', [SystemController::class, 'optimizeCache']);
            Route::get('/maintenance/status', [SystemController::class, 'maintenanceStatus']);
            Route::post('/maintenance/enable', [SystemController::class, 'maintenanceEnable']);
            Route::post('/maintenance/disable', [SystemController::class, 'maintenanceDisable']);
        });
    });
});

// =====================================================
// SETTINGS API (blade calls /api/settings/... without v1)
// =====================================================
Route::middleware(['auth:sanctum'])->prefix('settings')->group(function () {
    // Admin settings (boss only)
    Route::middleware('role:boss')->group(function () {
        Route::get('/general', [SettingsController::class, 'getGeneral']);
        Route::post('/general', [SettingsController::class, 'general']);
        Route::post('/email-templates', [SettingsController::class, 'emailTemplates']);
        Route::post('/email-templates/test', [SettingsController::class, 'emailTemplateTest']);
        Route::post('/email-templates/toggle', [SettingsController::class, 'emailTemplateToggle']);
        Route::post('/notifications', [SettingsController::class, 'notifications']);
        Route::post('/notifications/bulk', [SettingsController::class, 'notificationsBulk']);
        Route::get('/integrations', [SettingsController::class, 'integrations']);
        Route::post('/integrations/connect', [SettingsController::class, 'integrationConnect']);
        Route::post('/integrations/disconnect', [SettingsController::class, 'integrationDisconnect']);
        Route::post('/integrations/save-all', [SettingsController::class, 'integrationsSaveAll']);
    });

    // Staff preferences (boss + staff)
    Route::middleware('role:boss,staff')->group(function () {
        Route::match(['get', 'post'], '/staff/notifications', [SettingsController::class, 'staffNotifications']);
        Route::post('/staff/notifications/bulk', [SettingsController::class, 'staffNotificationsBulk']);
        Route::post('/staff/preferences', [SettingsController::class, 'staffPreferences']);
    });
});

// =====================================================
// DOCUMENTS SHORT ROUTES (blade calls /api/documents/... without v1)
// =====================================================
Route::middleware(['auth:sanctum', 'role:boss,staff'])->prefix('documents')->group(function () {
    Route::get('/admin-vault', [DocumentsController::class, 'adminVault']);
    Route::get('/{id}/preview', [DocumentsController::class, 'preview']);
    Route::put('/{id}/status', [DocumentsController::class, 'changeStatus']);
    Route::post('/{id}/status', [DocumentsController::class, 'changeStatus']);
});

// =====================================================
// N8N AUTOMATION ENDPOINTS (called by n8n workflows W01-W37)
// Auth: Sanctum Bearer token (n8n-service token)
// =====================================================
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

    // ADS sync per platform — W04-W07
    Route::post('/ads/sync/{platform}', [N8nAutomationController::class, 'adsSyncPlatform']);

    // SEO sync per source — W08-W09
    Route::post('/seo/sync/{source}', [N8nAutomationController::class, 'seoSync']);
    Route::post('/seo/network/check', [N8nAutomationController::class, 'seoNetworkCheck']);

    // Social automation — W11-W14
    Route::post('/social/accounts/sync', [N8nAutomationController::class, 'socialAccountsSync']);
    Route::post('/social/analytics/sync', [N8nAutomationController::class, 'socialAnalyticsSync']);
    Route::post('/social/posts/publish-scheduled', [N8nAutomationController::class, 'socialPublishScheduled']);
    Route::match(['get', 'post'], '/social/inbox/poll', [N8nAutomationController::class, 'socialInboxPoll']);

    // Brand automation — W15-W16
    Route::post('/brand/mentions/check', [N8nAutomationController::class, 'brandMentionsCheck']);
    Route::post('/brand/nap/check', [N8nAutomationController::class, 'brandNapCheck']);

    // Accounting automation — W17-W19
    Route::post('/accounting/bank/import', [N8nAutomationController::class, 'bankImport']);
    Route::post('/accounting/invoices/generate', [N8nAutomationController::class, 'invoiceGenerate']);
    Route::post('/accounting/invoices/{id}/send', [N8nAutomationController::class, 'invoiceSend']);
    Route::post('/accounting/tax/report', [N8nAutomationController::class, 'taxReport']);

    // Documents & Cases alerts — W20-W21
    Route::post('/documents/expiry-alerts', [N8nAutomationController::class, 'documentExpiryAlerts']);
    Route::post('/cases/deadline-alerts', [N8nAutomationController::class, 'caseDeadlineAlerts']);

    // Leads automation — W03
    Route::post('/leads/nurture', [N8nAutomationController::class, 'leadsNurture']);

    // News automation — W28-W33
    Route::post('/news/parse', [N8nAutomationController::class, 'newsParse']);
    Route::post('/news/rewrite-batch', [N8nAutomationController::class, 'newsRewriteBatch']);
    Route::post('/news/publish', [N8nAutomationController::class, 'newsPublish']);

    // Push notifications
    Route::post('/notifications/push', [N8nAutomationController::class, 'notificationPush']);

    // n8n workflow management
    Route::get('/n8n/workflows', [N8nAutomationController::class, 'workflowsList']);
    Route::post('/n8n/workflow/{code}/status', [N8nAutomationController::class, 'workflowStatus']);
    Route::post('/n8n/log-to-sheets', [N8nAutomationController::class, 'logToSheets']);
});

// =====================================================
// BOSS PORTAL (6 endpoints — loaded from separate file)
// =====================================================
require __DIR__ . '/api_boss_routes.php';

// =====================================================
// STAFF PORTAL (33 endpoints — loaded from separate file)
// =====================================================
require __DIR__ . '/api_staff_routes.php';

// ---------------------------------------------------------------
// Аннотация (RU):
// Master Router — ВСЕ 251+ endpoints в одном файле.
// 3 группы rate limiting: auth (5/min), webhooks (60/min), api (60/min per user).
// 5 уровней доступа: public, auth:sanctum, ability:module.action, ability:* (admin), staff.
// 24 секции: Auth, Dashboard, Leads, Clients, Cases, Tasks, Documents, Calendar,
// POS, Accounting, Ads, SEO, Social, Brand, Landings, News, Notifications,
// Reports, Users (admin), Audit (admin), System (admin), Boss Portal, Staff Portal.
// RBAC middleware: ability:leads.create, ability:pos.approve, ability:* (admin only).
// Файл: routes/api_routes.php
// ---------------------------------------------------------------
