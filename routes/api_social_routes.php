<?php

// =====================================================
// FILE: routes/api.php (append — Social + Brand Module)
// =====================================================

use App\Http\Controllers\Api\V1\SocialController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/social')->middleware(['auth:sanctum'])->group(function () {

    Route::get('/accounts', [SocialController::class, 'accounts'])
         ->name('social.accounts');

    Route::post('/publish', [SocialController::class, 'publish'])
         ->name('social.publish');

    Route::get('/inbox', [SocialController::class, 'inbox'])
         ->name('social.inbox');

    Route::get('/posts', [SocialController::class, 'posts'])
         ->name('social.posts');

    Route::get('/posts/{id}/analytics', [SocialController::class, 'postAnalytics'])
         ->name('social.posts.analytics');

    Route::get('/calendar', [SocialController::class, 'calendar'])
         ->name('social.calendar');

    Route::post('/sync', [SocialController::class, 'sync'])
         ->name('social.sync');
});

// ---------------------------------------------------------------
// Аннотация (RU):
// API маршруты Social модуля. 7 endpoints, все auth:sanctum.
// Unified Posting: POST /publish с массивом platforms[].
// Unified Inbox: GET /inbox (FB + IG + YouTube + Telegram).
// Content Calendar: GET /calendar (?date_from=&date_to=).
// Файл: routes/api_social_routes.php
// ---------------------------------------------------------------
