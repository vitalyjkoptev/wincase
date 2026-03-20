<?php

// =====================================================
// FILE: routes/api.php (append — Dashboard Module)
// =====================================================

use App\Http\Controllers\Api\V1\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/dashboard')->middleware(['auth:sanctum'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
         ->name('dashboard.index');

    Route::get('/kpi', [DashboardController::class, 'kpi'])
         ->name('dashboard.kpi');

    Route::get('/cases', [DashboardController::class, 'cases'])
         ->name('dashboard.cases');

    Route::get('/leads', [DashboardController::class, 'leads'])
         ->name('dashboard.leads');

    Route::get('/finance', [DashboardController::class, 'finance'])
         ->name('dashboard.finance');

    Route::get('/ads', [DashboardController::class, 'ads'])
         ->name('dashboard.ads');

    Route::get('/social', [DashboardController::class, 'social'])
         ->name('dashboard.social');

    Route::get('/seo', [DashboardController::class, 'seo'])
         ->name('dashboard.seo');

    Route::get('/accounting', [DashboardController::class, 'accounting'])
         ->name('dashboard.accounting');

    Route::get('/automation', [DashboardController::class, 'automation'])
         ->name('dashboard.automation');
});

// ---------------------------------------------------------------
// Аннотация (RU):
// API маршруты Dashboard модуля. 10 endpoints, все auth:sanctum.
// GET / — полный dashboard. GET /kpi — только KPI bar.
// 8 отдельных секций: cases, leads, finance, ads, social, seo, accounting, automation.
// Файл: routes/api_dashboard_routes.php
// ---------------------------------------------------------------
