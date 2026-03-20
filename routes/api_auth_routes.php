<?php

// =====================================================
// FILE: routes/api.php (append — Auth & Users Module)
// =====================================================

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UsersController;
use Illuminate\Support\Facades\Route;

// =====================================================
// AUTH (public — no auth required)
// =====================================================
Route::prefix('v1/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register'); // Only creates 'user' role
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword'])->name('auth.password.forgot');
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('auth.password.reset');
});

// =====================================================
// AUTH (authenticated)
// =====================================================
Route::prefix('v1/auth')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
    Route::post('/2fa/enable', [AuthController::class, 'enable2FA'])->name('auth.2fa.enable');
    Route::post('/2fa/confirm', [AuthController::class, 'confirm2FA'])->name('auth.2fa.confirm');
    Route::post('/2fa/disable', [AuthController::class, 'disable2FA'])->name('auth.2fa.disable');
});

// =====================================================
// USERS (admin only)
// =====================================================
Route::prefix('v1/users')->middleware(['auth:sanctum', 'ability:*'])->group(function () {
    Route::get('/', [UsersController::class, 'index'])->name('users.index');
    Route::get('/roles', [UsersController::class, 'roles'])->name('users.roles');
    Route::get('/team-stats', [UsersController::class, 'teamStats'])->name('users.team-stats');
    Route::get('/{id}', [UsersController::class, 'show'])->name('users.show');
    Route::post('/', [UsersController::class, 'store'])->name('users.store');
    Route::put('/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::post('/{id}/role', [UsersController::class, 'changeRole'])->name('users.role');
    Route::post('/{id}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/{id}/activate', [UsersController::class, 'activate'])->name('users.activate');
    Route::delete('/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
});

// ---------------------------------------------------------------
// Аннотация (RU):
// 18 маршрутов Auth & Users.
// Public (3): login, forgot password, reset password.
// Auth required (5): logout, me, 2FA enable/confirm/disable.
// Admin only (10): CRUD users, roles, team stats, change role, activate/deactivate.
// ability:* — Sanctum middleware: только admin (все abilities).
// Файл: routes/api_auth_routes.php
// ---------------------------------------------------------------
