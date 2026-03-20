<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebAuthController;
use App\Http\Controllers\Web\WebDashboardController;

// =====================================================
// AUTH (public)
// =====================================================
Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);

// =====================================================
// AUTHENTICATED ADMIN PANEL
// =====================================================
Route::middleware(['auth'])->group(function () {

    Route::match(['get', 'post'], '/logout', [WebAuthController::class, 'logout'])->name('logout');

    // CRM Dashboard — main page
    Route::get('/', function () {
        $user = auth()->user();
        if ($user->role === 'staff') {
            return view('employee.dashboard');
        }
        return view('crm.dashboard');
    });

    // CRM Pages
    Route::get('crm-{page}', [WebDashboardController::class, 'crm'])->where('page', '[a-z\-]+');

    // Finance Pages
    Route::get('finance-{page}', [WebDashboardController::class, 'finance'])->where('page', '[a-z\-]+');

    // Marketing Pages
    Route::get('marketing-{page}', [WebDashboardController::class, 'marketing'])->where('page', '[a-z\-]+');

    // Content Pages
    Route::get('content-{page}', [WebDashboardController::class, 'content'])->where('page', '[a-z\-]+');

    // Analytics Pages
    Route::get('analytics-{page}', [WebDashboardController::class, 'analytics'])->where('page', '[a-z\-]+');

    // Jobs Pages (WinCaseJobs Portal)
    Route::get('jobs-{page}', [WebDashboardController::class, 'jobs'])->where('page', '[a-z\-]+');

    // Admin Pages (boss only)
    Route::get('admin-{page}', [WebDashboardController::class, 'admin'])->where('page', '[a-z\-]+');

    // Staff Portal Pages
    Route::get('staff-{page}', [WebDashboardController::class, 'staff'])->where('page', '[a-z0-9\-]+');

    // Client Portal Pages
    Route::get('client-{page}', [WebDashboardController::class, 'client'])->where('page', '[a-z0-9\-]+');
});

// Auth pages (password reset etc.)
Route::get('auth-{page}', function (string $page) {
    $view = "auth.{$page}";
    if (view()->exists($view)) {
        return view($view);
    }
    abort(404);
})->where('page', '[a-z0-9\-]+');
