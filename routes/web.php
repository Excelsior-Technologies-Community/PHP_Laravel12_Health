<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

// Main Health Dashboard
Route::get('/health', [HealthController::class, 'index']);

// Spatie JSON health endpoint
Route::get('/health-json', HealthCheckJsonResultsController::class);

// Default Spatie Health Dashboard
Route::get('/health-dashboard', HealthCheckResultsController::class);

// Health History & Statistics
Route::get('/health-history', [HealthController::class, 'history']);

// Health Incident & Failure Log
Route::get('/health-incidents', [HealthController::class, 'incidents']);

// System Resource Health Monitoring
Route::get('/health-system', [HealthController::class, 'system']);

// System Monitoring JSON endpoint
Route::get('/health-system-json', [HealthController::class, 'systemJson']);