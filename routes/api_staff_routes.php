<?php

use App\Http\Controllers\Api\V1\StaffController;
use Illuminate\Support\Facades\Route;

// =====================================================
// STAFF PORTAL API — 30 endpoints
// All authenticated, employee-scoped
// Everything syncs to boss CRM automatically
// =====================================================

Route::prefix('v1/staff')->middleware(['auth:sanctum'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [StaffController::class, 'dashboard']);

    // Multichat — unified inbox for staff's assigned clients
    Route::get('/multichat', [StaffController::class, 'multichat']);
    Route::get('/multichat/{clientId}/messages', [StaffController::class, 'multichatMessages']);
    Route::post('/multichat/{clientId}/messages', [StaffController::class, 'multichatSend']);

    // My Clients
    Route::get('/clients', [StaffController::class, 'myClients']);
    Route::get('/clients/{id}', [StaffController::class, 'clientDetail']);

    // Client Communication (→ syncs to CRM)
    Route::get('/clients/{clientId}/messages', [StaffController::class, 'clientMessages']);
    Route::post('/clients/{clientId}/messages', [StaffController::class, 'sendClientMessage']);
    Route::post('/clients/{clientId}/messages/read', [StaffController::class, 'markClientRead']);
    Route::post('/clients/{clientId}/call-log', [StaffController::class, 'logClientCall']);
    Route::post('/clients/{clientId}/request-document', [StaffController::class, 'requestDocument']);

    // Boss Chat (encrypted)
    Route::get('/boss-chat', [StaffController::class, 'bossChat']);
    Route::post('/boss-chat', [StaffController::class, 'sendToBoss']);

    // Team Messages
    Route::get('/team', [StaffController::class, 'teamConversations']);
    Route::get('/team/{partnerId}/messages', [StaffController::class, 'teamMessages']);
    Route::post('/team/{partnerId}/messages', [StaffController::class, 'sendTeamMessage']);

    // My Cases
    Route::get('/cases', [StaffController::class, 'myCases']);
    Route::get('/cases/{id}', [StaffController::class, 'caseDetail']);
    Route::post('/cases/{caseId}/note', [StaffController::class, 'addCaseNote']);
    Route::post('/cases/{caseId}/status', [StaffController::class, 'updateCaseStatus']);

    // My Tasks
    Route::get('/tasks', [StaffController::class, 'myTasks']);
    Route::post('/tasks/{taskId}/complete', [StaffController::class, 'completeTask']);
    Route::post('/tasks/{taskId}/status', [StaffController::class, 'updateTaskStatus']);

    // Documents
    Route::get('/documents', [StaffController::class, 'myDocuments']);
    Route::post('/documents/upload', [StaffController::class, 'uploadDocument']);

    // Time Tracking
    Route::post('/time/clock-in', [StaffController::class, 'clockIn']);
    Route::post('/time/clock-out', [StaffController::class, 'clockOut']);
    Route::get('/time/history', [StaffController::class, 'timeHistory']);

    // Calendar
    Route::get('/calendar', [StaffController::class, 'calendar']);

    // Profile
    Route::get('/profile', [StaffController::class, 'profile']);
    Route::put('/profile', [StaffController::class, 'updateProfile']);

    // Knowledge Base
    Route::get('/knowledge', [StaffController::class, 'knowledgeBase']);
});
