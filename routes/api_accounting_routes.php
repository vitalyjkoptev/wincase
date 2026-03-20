<?php

// =====================================================
// FILE: routes/api.php (append to existing routes)
// Accounting Module — Laravel 12
// =====================================================

use App\Http\Controllers\Api\V1\AccountingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/accounting')->middleware(['auth:sanctum'])->group(function () {

    // --- Tax Configuration ---
    Route::get('/tax-config', [AccountingController::class, 'taxConfig'])
         ->name('accounting.tax-config');

    // --- Quick Calculator ---
    Route::post('/calculate', [AccountingController::class, 'calculate'])
         ->name('accounting.calculate');

    Route::post('/compare-regimes', [AccountingController::class, 'compareRegimes'])
         ->name('accounting.compare-regimes');

    // --- Reports ---
    Route::prefix('reports')->group(function () {
        Route::get('/', [AccountingController::class, 'listReports'])
             ->name('accounting.reports.index');

        Route::post('/monthly', [AccountingController::class, 'generateMonthlyReport'])
             ->name('accounting.reports.monthly');

        Route::post('/vat', [AccountingController::class, 'generateVatReport'])
             ->name('accounting.reports.vat');

        Route::post('/zus', [AccountingController::class, 'generateZusReport'])
             ->name('accounting.reports.zus');

        Route::post('/annual', [AccountingController::class, 'generateAnnualReport'])
             ->name('accounting.reports.annual');

        Route::post('/profit-loss', [AccountingController::class, 'generateProfitLoss'])
             ->name('accounting.reports.profit-loss');

        Route::get('/{report}', [AccountingController::class, 'showReport'])
             ->name('accounting.reports.show');

        Route::patch('/{report}/submit', [AccountingController::class, 'markSubmitted'])
             ->name('accounting.reports.submit');
    });

    // --- Expenses ---
    Route::get('/expenses', [AccountingController::class, 'listExpenses'])
         ->name('accounting.expenses.index');

    Route::post('/expenses', [AccountingController::class, 'storeExpense'])
         ->name('accounting.expenses.store');

    Route::delete('/expenses/{expense}', [AccountingController::class, 'destroyExpense'])
         ->name('accounting.expenses.destroy');

    // --- Accounting Periods ---
    Route::get('/periods', [AccountingController::class, 'listPeriods'])
         ->name('accounting.periods.index');

    Route::patch('/periods/{period}/close', [AccountingController::class, 'closePeriod'])
         ->name('accounting.periods.close');
});

// ---------------------------------------------------------------
// Аннотация (RU):
// API маршруты бухгалтерского модуля. Все требуют auth:sanctum.
// 16 endpoints: конфигурация, калькулятор, 5 типов отчётов,
// управление отчётами, CRUD расходов, периоды.
// Добавить в routes/api.php после POS маршрутов.
// Файл: routes/api_accounting.php (или append к routes/api.php)
// ---------------------------------------------------------------
