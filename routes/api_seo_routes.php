<?php

// =====================================================
// FILE: routes/api.php (append — SEO Module)
// =====================================================

use App\Http\Controllers\Api\V1\SeoController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/seo')->middleware(['auth:sanctum'])->group(function () {

    Route::get('/overview', [SeoController::class, 'overview'])
         ->name('seo.overview');

    Route::get('/keywords', [SeoController::class, 'keywords'])
         ->name('seo.keywords');

    Route::get('/network', [SeoController::class, 'network'])
         ->name('seo.network');

    Route::get('/backlinks', [SeoController::class, 'backlinks'])
         ->name('seo.backlinks');

    Route::get('/reviews', [SeoController::class, 'reviews'])
         ->name('seo.reviews');

    Route::get('/brand', [SeoController::class, 'brand'])
         ->name('seo.brand');
});

// ---------------------------------------------------------------
// Аннотация (RU):
// API маршруты SEO модуля. 6 endpoints, все auth:sanctum.
// Query params: ?domain=, ?date_from=, ?date_to=, ?days=, ?limit=
// Файл: routes/api_seo_routes.php
// ---------------------------------------------------------------
