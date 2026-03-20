<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientPortalController;

// CRM Dashboard — главная
Route::get('/', function () {
    return view('crm.dashboard');
});

// CRM Pages
Route::get('crm-{page}', [DashboardController::class, 'crm'])->where('page', '[a-z\-]+');

// Finance Pages
Route::get('finance-{page}', [DashboardController::class, 'finance'])->where('page', '[a-z\-]+');

// Marketing Pages
Route::get('marketing-{page}', [DashboardController::class, 'marketing'])->where('page', '[a-z\-]+');

// Content Pages
Route::get('content-{page}', [DashboardController::class, 'content'])->where('page', '[a-z\-]+');

// Analytics Pages
Route::get('analytics-{page}', [DashboardController::class, 'analytics'])->where('page', '[a-z\-]+');

// Jobs Pages (WinCaseJobs Portal)
Route::get('jobs-{page}', [DashboardController::class, 'jobs'])->where('page', '[a-z\-]+');

// Admin Pages
Route::get('admin-{page}', [DashboardController::class, 'admin'])->where('page', '[a-z\-]+');

// Auth Pages (login, signup, etc.)
Route::get('auth-{page}', [DashboardController::class, 'auth'])->where('page', '[a-z0-9\-]+');

// Client Portal Pages
Route::get('client-{page}', [DashboardController::class, 'client'])->where('page', '[a-z0-9\-]+');

// Staff Portal Pages
Route::get('staff-{page}', [DashboardController::class, 'staff'])->where('page', '[a-z0-9\-]+');

// Client Portal API
Route::post('api/client/register', [ClientPortalController::class, 'register']);
Route::get('api/client/registrations', [ClientPortalController::class, 'index']);
Route::get('api/client/registrations/{id}', [ClientPortalController::class, 'show']);
Route::patch('api/client/registrations/{id}/status', [ClientPortalController::class, 'updateStatus']);
Route::delete('api/client/registrations/{id}', [ClientPortalController::class, 'destroy']);
