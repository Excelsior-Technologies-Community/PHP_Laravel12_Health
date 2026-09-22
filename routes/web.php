<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;


/*
|--------------------------------------------------------------------------
| Main Health Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/health', [
    HealthController::class,
    'index'
])->name('health');


/*
|--------------------------------------------------------------------------
| Manual Health Check
|--------------------------------------------------------------------------
*/

Route::post('/health/run-check', [
    HealthController::class,
    'runCheck'
])->name('health.run');


/*
|--------------------------------------------------------------------------
| Spatie JSON Health Endpoint
|--------------------------------------------------------------------------
*/

Route::get('/health-json', HealthCheckJsonResultsController::class)
    ->name('health.json');


/*
|--------------------------------------------------------------------------
| Default Spatie Health Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/health-dashboard', HealthCheckResultsController::class)
    ->name('health.spatie');


/*
|--------------------------------------------------------------------------
| Health History
|--------------------------------------------------------------------------
*/

Route::get('/health-history', [
    HealthController::class,
    'history'
])->name('health.history');


/*
|--------------------------------------------------------------------------
| Export Health History
|--------------------------------------------------------------------------
*/

Route::get('/health-history/export', [
    HealthController::class,
    'exportHistory'
])->name('health.history.export');


/*
|--------------------------------------------------------------------------
| Health Incidents
|--------------------------------------------------------------------------
*/

Route::get('/health-incidents', [
    HealthController::class,
    'incidents'
])->name('health.incidents');


/*
|--------------------------------------------------------------------------
| Export Health Incidents
|--------------------------------------------------------------------------
*/

Route::get('/health-incidents/export', [
    HealthController::class,
    'exportIncidents'
])->name('health.incidents.export');


/*
|--------------------------------------------------------------------------
| System Resource Health
|--------------------------------------------------------------------------
*/

Route::get('/health-system', [
    HealthController::class,
    'system'
])->name('health.system');


/*
|--------------------------------------------------------------------------
| System Monitoring JSON
|--------------------------------------------------------------------------
*/

Route::get('/health-system-json', [
    HealthController::class,
    'systemJson'
])->name('health.system.json');