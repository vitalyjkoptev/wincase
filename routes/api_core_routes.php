<?php

// =====================================================
// FILE: routes/api.php (append — Core CRM Module)
// =====================================================

use App\Http\Controllers\Api\V1\ClientsController;
use App\Http\Controllers\Api\V1\CasesController;
use App\Http\Controllers\Api\V1\TasksController;
use App\Http\Controllers\Api\V1\DocumentsController;
use App\Http\Controllers\Api\V1\CalendarController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {

    // =====================================================
    // CLIENTS (8 endpoints)
    // =====================================================
    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientsController::class, 'index'])->name('clients.index');
        Route::get('/statistics', [ClientsController::class, 'statistics'])->name('clients.statistics');
        Route::get('/{id}', [ClientsController::class, 'show'])->name('clients.show');
        Route::post('/', [ClientsController::class, 'store'])->name('clients.store');
        Route::put('/{id}', [ClientsController::class, 'update'])->name('clients.update');
        Route::post('/{id}/archive', [ClientsController::class, 'archive'])->name('clients.archive');
        Route::post('/{id}/activate', [ClientsController::class, 'activate'])->name('clients.activate');
    });

    // =====================================================
    // CASES (8 endpoints)
    // =====================================================
    Route::prefix('cases')->group(function () {
        Route::get('/', [CasesController::class, 'index'])->name('cases.index');
        Route::get('/deadlines', [CasesController::class, 'deadlines'])->name('cases.deadlines');
        Route::get('/statistics', [CasesController::class, 'statistics'])->name('cases.statistics');
        Route::get('/{id}', [CasesController::class, 'show'])->name('cases.show');
        Route::post('/', [CasesController::class, 'store'])->name('cases.store');
        Route::put('/{id}', [CasesController::class, 'update'])->name('cases.update');
        Route::post('/{id}/status', [CasesController::class, 'changeStatus'])->name('cases.status');
        Route::post('/{id}/assign', [CasesController::class, 'assign'])->name('cases.assign');
    });

    // =====================================================
    // TASKS (7 endpoints)
    // =====================================================
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TasksController::class, 'index'])->name('tasks.index');
        Route::get('/my', [TasksController::class, 'myTasks'])->name('tasks.my');
        Route::get('/overdue', [TasksController::class, 'overdue'])->name('tasks.overdue');
        Route::get('/statistics', [TasksController::class, 'statistics'])->name('tasks.statistics');
        Route::post('/', [TasksController::class, 'store'])->name('tasks.store');
        Route::put('/{id}', [TasksController::class, 'update'])->name('tasks.update');
        Route::post('/{id}/complete', [TasksController::class, 'complete'])->name('tasks.complete');
    });

    // =====================================================
    // DOCUMENTS (6 endpoints)
    // =====================================================
    Route::prefix('documents')->group(function () {
        Route::get('/client/{clientId}', [DocumentsController::class, 'byClient'])->name('documents.by-client');
        Route::get('/case/{caseId}', [DocumentsController::class, 'byCase'])->name('documents.by-case');
        Route::get('/expiring', [DocumentsController::class, 'expiring'])->name('documents.expiring');
        Route::post('/upload', [DocumentsController::class, 'upload'])->name('documents.upload');
        Route::get('/{id}/download', [DocumentsController::class, 'download'])->name('documents.download');
        Route::delete('/{id}', [DocumentsController::class, 'destroy'])->name('documents.destroy');
    });

    // =====================================================
    // CALENDAR (6 endpoints)
    // =====================================================
    Route::prefix('calendar')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/today', [CalendarController::class, 'today'])->name('calendar.today');
        Route::get('/upcoming', [CalendarController::class, 'upcoming'])->name('calendar.upcoming');
        Route::post('/', [CalendarController::class, 'store'])->name('calendar.store');
        Route::put('/{id}', [CalendarController::class, 'update'])->name('calendar.update');
        Route::delete('/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
    });
});

// ---------------------------------------------------------------
// Аннотация (RU):
// API маршруты Core CRM. 35 endpoints, все auth:sanctum.
// Clients (8): CRUD + archive/activate + statistics.
// Cases (8): CRUD + status transitions + assign + deadlines + statistics.
// Tasks (7): CRUD + complete + my tasks + overdue + statistics.
// Documents (6): by client/case + upload + download + delete + expiring.
// Calendar (6): CRUD + today + upcoming.
// Файл: routes/api_core_routes.php
// ---------------------------------------------------------------
