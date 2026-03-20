<?php

// =====================================================
// FILE: routes/api.php (append to existing routes)
// POS Terminal Module — Laravel 12
// =====================================================

use App\Http\Controllers\Api\V1\PosController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/pos')->middleware(['auth:sanctum'])->group(function () {

    // --- Operator Actions ---
    Route::post('/receive', [PosController::class, 'receive'])
         ->name('pos.receive');

    // --- Owner Decision Actions ---
    Route::get('/pending', [PosController::class, 'pending'])
         ->name('pos.pending');

    Route::get('/daily-report', [PosController::class, 'dailyReport'])
         ->name('pos.daily-report');

    Route::get('/tax-report', [PosController::class, 'taxReport'])
         ->name('pos.tax-report');

    Route::get('/history', [PosController::class, 'history'])
         ->name('pos.history');

    // --- Single Transaction ---
    Route::get('/{posTransaction}', [PosController::class, 'show'])
         ->name('pos.show');

    Route::patch('/{posTransaction}/review', [PosController::class, 'markForReview'])
         ->name('pos.review');

    Route::patch('/{posTransaction}/approve', [PosController::class, 'approve'])
         ->name('pos.approve');

    Route::patch('/{posTransaction}/reject', [PosController::class, 'reject'])
         ->name('pos.reject');

    Route::post('/{posTransaction}/process', [PosController::class, 'process'])
         ->name('pos.process');

    Route::patch('/{posTransaction}/refund', [PosController::class, 'refund'])
         ->name('pos.refund');
});

// ---------------------------------------------------------------
// Аннотация (RU):
// API маршруты POS-модуля. Все требуют auth:sanctum.
// Порядок маршрутов важен: статические (/pending, /daily-report, /tax-report,
// /history) ПЕРЕД динамическими (/{posTransaction}), чтобы Laravel
// не интерпретировал "pending" как ID транзакции.
// Добавить в routes/api.php после существующих маршрутов.
// Файл: routes/api_pos.php (или append к routes/api.php)
// ---------------------------------------------------------------
