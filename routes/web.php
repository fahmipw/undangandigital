<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\LegacyApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Admin Panel Routes (Matching Old URLs) ---
Route::get('login', [AdminController::class, 'showLogin'])->name('login');
Route::post('login', [AdminController::class, 'login']);
Route::get('logout', [AdminController::class, 'logout']);

Route::middleware('auth:admin')->group(function () {
    Route::get('admin', [AdminController::class, 'index']);
    
    // Legacy Admin API
    Route::any('api/admin_api', [LegacyApiController::class, 'adminApi'])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
});

// --- Legacy Frontend APIs ---
Route::any('api/rsvp', [ApiController::class, 'rsvp'])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
Route::any('api/ucapan', [ApiController::class, 'ucapan'])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
Route::any('api/music', [ApiController::class, 'music']);
Route::any('api/get_settings', [ApiController::class, 'getSettings']);

// --- QR Check-in ---
Route::any('api/checkin', [ApiController::class, 'checkin'])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
Route::get('api/checkin_stats', [ApiController::class, 'checkinStats']);

// --- Generator Route ---
Route::get('/generator/{slug}', [FrontController::class, 'generator']);
Route::post('api/generate_guest', [ApiController::class, 'generateGuest'])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

// --- Frontend Routes ---
Route::get('/', [FrontController::class, 'index']);
Route::get('/{slug}', [FrontController::class, 'index']);
