#  PHP_Laravel12_Healthh

![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![Spatie Health](https://img.shields.io/badge/Spatie-Laravel%20Health-4CAF50)
![License](https://img.shields.io/badge/License-MIT-green)

---

##  Overview

**PHP_Laravel12_Health** is a Laravel 12 project that integrates the Spatie Laravel Health package to monitor application health.

It provides:

* A custom Blade-based health dashboard
* A JSON health endpoint
* The default Spatie health dashboard
* Auto-refreshing UI for real-time monitoring

This setup is suitable for development, staging, and production environments.

---

##  Features

*  Database Health Check
*  Cache Health Check
*  Debug Mode Verification
*  Environment Verification
*  Custom Dark-Themed Blade Dashboard
*  JSON Health Endpoint
*  Auto-Refreshing UI (Every 5 Seconds)
*  Optional Spatie Default Dashboard

---

##  Folder Structure 

```
app/
 ├── Http/
 │    └── Controllers/
 │         └── HealthController.php
 │
 ├── Providers/
 │    └── AppServiceProvider.php
 │
routes/
 └── web.php

resources/
 └── views/
      └── health.blade.php
```

---

#  Installation & Setup Guide

---

## 1️ Project Installation

### Step 1: Create New Laravel 12 Project

```bash
composer create-project laravel/laravel Laravel12_Health
```

Move into project directory:

```bash
cd Laravel12_Health
```

Start development server:

```bash
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

---

## 2️ Environment Configuration

Open the `.env` file and configure:

```env
APP_NAME=Laravel
APP_ENV=production
APP_KEY=Your_Key
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Clear configuration cache:

```bash
php artisan config:clear
```

---

## 3️ Install Spatie Laravel Health Package

Install package:

```bash
composer require spatie/laravel-health
```

Publish configuration:

```bash
php artisan vendor:publish --tag="health-config"
```

Publish migrations:

```bash
php artisan vendor:publish --tag="health-migrations"
```

Run migrations:

```bash
php artisan migrate
```

---

## 4️ Register Health Checks

Open:

```
app/Providers/AppServiceProvider.php
```

Update the `boot()` method:

```php
use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;

public function boot(): void
{
    Health::checks([
        DatabaseCheck::new(),
        CacheCheck::new(),
        DebugModeCheck::new(),
        EnvironmentCheck::new(),
    ]);
}
```

---

## 5️ Create Health Controller

Run:

```bash
php artisan make:controller HealthController
```

File:

```
app/Http/Controllers/HealthController.php
```

```php
<?php

namespace App\Http\Controllers;

class HealthController extends Controller
{
    public function index()
    {
        return view('health');
    }
}
```

---

## 6️ Define Routes

Open:

```
routes/web.php
```

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

// Custom Blade Dashboard
Route::get('/health', [HealthController::class, 'index']);

// JSON Endpoint
Route::get('/health-json', HealthCheckJsonResultsController::class);

// Optional Spatie Default Dashboard
Route::get('/health-dashboard', HealthCheckResultsController::class);
```

---

## 7️ Create Blade Dashboard

Create file:

resources/views/health.blade.php
```
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laravel Health Dashboard</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #fff;
            padding: 40px;
        }

        h1 {
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .badge {
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: bold;
            display: inline-block;
            margin: 20px 0;
        }

        .badge-ok {
            background: #16a34a;
        }

        .badge-failed {
            background: #dc2626;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ok {
            background: #16a34a;
            padding: 6px 15px;
            border-radius: 20px;
        }

        .failed {
            background: #dc2626;
            padding: 6px 15px;
            border-radius: 20px;
        }

        .summary {
            font-size: 13px;
            opacity: 0.7;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>💙 Laravel Health Dashboard</h1>

    <div id="overall"></div>
    <div id="results"></div>

</div>

<script>
function loadHealth() {
    fetch('/health-json') 
        .then(res => res.json())
        .then(data => {

            let results = data.checkResults;
            let hasFailure = results.some(r => r.status !== 'ok');

            let overall = document.getElementById('overall');
            overall.innerHTML = `
                <div style="text-align:center;">
                    <span class="badge ${hasFailure ? 'badge-failed' : 'badge-ok'}">
                        ${hasFailure ? 'FAILED' : 'OK'}
                    </span>
                </div>
            `;

            let container = document.getElementById('results');
            container.innerHTML = '';

            results.forEach(result => {
                container.innerHTML += `
                    <div class="card">
                        <div>
                            <strong>${result.label}</strong>
                            <div class="summary">${result.shortSummary}</div>
                        </div>
                        <div class="${result.status === 'ok' ? 'ok' : 'failed'}">
                            ${result.status.toUpperCase()}
                        </div>
                    </div>
                `;
            });
        });
}

loadHealth();
setInterval(loadHealth, 5000);
</script>

</body>
</html>

```
---

## 8️ Final URLs

### 🔹 Custom Dashboard

```
http://127.0.0.1:8000/health
```
<img width="1049" height="601" alt="Screenshot 2026-02-12 160315" src="https://github.com/user-attachments/assets/0398906d-f8c2-4008-93c2-26567a3a4537" />


### 🔹 JSON Endpoint

```
http://127.0.0.1:8000/health-json
```
<img width="1919" height="110" alt="Screenshot 2026-02-12 160350" src="https://github.com/user-attachments/assets/30a87593-2352-4074-a23a-8920b52c93a1" />


### 🔹 Default Spatie Dashboard

```
http://127.0.0.1:8000/health-dashboard
```
<img width="1416" height="507" alt="Screenshot 2026-02-12 160327" src="https://github.com/user-attachments/assets/2d708763-31d2-488c-885c-4d47b0a4823f" />

---

#  Final Result

The application now includes:

* Database health check
* Cache health check
* Debug mode verification
* Environment verification
* Custom Blade dashboard
* JSON endpoint
* Auto-refreshing UI

---




