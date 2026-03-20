<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CaseController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('cases', CaseController::class);
    Route::apiResource('documents', App\Http\Controllers\Api\DocumentController::class);
    Route::apiResource('hearings', App\Http\Controllers\Api\HearingController::class);
    Route::apiResource('invoices', App\Http\Controllers\Api\InvoiceController::class);
    Route::apiResource('payments', App\Http\Controllers\Api\PaymentController::class);
    Route::apiResource('social/posts', App\Http\Controllers\Api\SocialMediaController::class);
    Route::post('ai/generate', [App\Http\Controllers\Api\AIController::class, 'generate']);
    Route::apiResource('verifications', App\Http\Controllers\Api\VerificationController::class);
    Route::apiResource('workflows', App\Http\Controllers\Api\N8NController::class);
});
