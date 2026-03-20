<?php

use App\Http\Controllers\Api\V1\BossController;
use Illuminate\Support\Facades\Route;

// =====================================================
// BOSS PORTAL API — 6 endpoints
// Only boss roles (boss, admin_boss) — ability:*
// Full access to all clients, workers, finances
// =====================================================

Route::prefix('v1/boss')->middleware(['auth:sanctum', 'ability:*'])->group(function () {

    // Multichat — unified inbox across all channels
    Route::get('/multichat', [BossController::class, 'multichat']);
    Route::get('/multichat/{clientId}/messages', [BossController::class, 'multichatMessages']);
    Route::post('/multichat/{clientId}/messages', [BossController::class, 'multichatSend']);

    // Workers — all employees overview
    Route::get('/workers', [BossController::class, 'workers']);

    // All Clients — boss sees everyone
    Route::get('/clients', [BossController::class, 'clients']);

    // Finances — P&L summary
    Route::get('/finances', [BossController::class, 'finances']);
});
