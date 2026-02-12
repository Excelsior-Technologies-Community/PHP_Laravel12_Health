<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

// Blade Dashboard
Route::get('/health', [HealthController::class, 'index']);

// JSON endpoint (used by JS)
Route::get('/health-json', HealthCheckJsonResultsController::class);

// Optional: Spatie default dashboard
Route::get('/health-dashboard', HealthCheckResultsController::class);
