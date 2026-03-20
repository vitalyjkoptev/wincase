<?php

// =====================================================
// FILE: routes/api.php (append — Ads Module)
// =====================================================

use App\Http\Controllers\Api\V1\AdsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/ads')->middleware(['auth:sanctum'])->group(function () {

    Route::get('/overview', [AdsController::class, 'overview'])
         ->name('ads.overview');

    Route::get('/budget', [AdsController::class, 'budget'])
         ->name('ads.budget');

    Route::post('/sync', [AdsController::class, 'sync'])
         ->name('ads.sync');

    Route::get('/{platform}', [AdsController::class, 'platform'])
         ->name('ads.platform');

    Route::get('/{platform}/campaigns', [AdsController::class, 'campaigns'])
         ->name('ads.campaigns');
});

// ---------------------------------------------------------------
// Аннотация (RU):
// API маршруты модуля рекламы. Все auth:sanctum.
// /overview и /budget ПЕРЕД /{platform} (иначе Laravel парсит
// "overview" как значение {platform}).
// POST /sync — ручная синхронизация (для тестов и admin panel).
// Файл: routes/api_ads_routes.php
// ---------------------------------------------------------------
