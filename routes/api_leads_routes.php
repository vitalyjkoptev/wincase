<?php

// =====================================================
// FILE: routes/api.php (append to existing routes)
// Leads Module — Laravel 12
// =====================================================

use App\Http\Controllers\Api\V1\LeadController;
use Illuminate\Support\Facades\Route;

// --- Public Lead Submission (rate-limited, honeypot, reCAPTCHA) ---
Route::prefix('v1/leads')->group(function () {

    Route::post('/', [LeadController::class, 'store'])
         ->middleware(['throttle:lead_submit'])
         ->name('leads.store.public');
});

// --- Admin Lead Management (auth required) ---
Route::prefix('v1/leads')->middleware(['auth:sanctum'])->group(function () {

    Route::get('/', [LeadController::class, 'index'])
         ->name('leads.index');

    Route::get('/funnel', [LeadController::class, 'funnel'])
         ->name('leads.funnel');

    Route::get('/stats', [LeadController::class, 'stats'])
         ->name('leads.stats');

    Route::get('/{lead}', [LeadController::class, 'show'])
         ->name('leads.show');

    Route::patch('/{lead}', [LeadController::class, 'update'])
         ->name('leads.update');

    Route::delete('/{lead}', [LeadController::class, 'destroy'])
         ->name('leads.destroy');

    Route::post('/{lead}/convert', [LeadController::class, 'convert'])
         ->name('leads.convert');
});

// ---------------------------------------------------------------
// Аннотация (RU):
// API маршруты модуля лидов.
// POST /leads — ПУБЛИЧНЫЙ (throttle 10/min/IP, honeypot, reCAPTCHA).
// Все остальные — auth:sanctum (admin).
// Важно: /funnel и /stats ПЕРЕД /{lead} — иначе Laravel
// интерпретирует "funnel" как lead ID.
// Добавить в routes/api.php после POS + Accounting маршрутов.
// Файл: routes/api_leads_routes.php
// ---------------------------------------------------------------
