<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>System Resource Health</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #0f172a;
            font-family: Arial, sans-serif;
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            color: #94a3b8;
            margin-bottom: 30px;
        }

        .navigation {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .navigation a {
            text-decoration: none;
            color: white;
            background: #1e293b;
            padding: 10px 15px;
            border-radius: 7px;
            border: 1px solid #334155;
        }

        .grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(240px, 1fr));

            gap: 18px;
        }

        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 22px;
        }

        .card h2 {
            font-size: 17px;
            margin-top: 0;
            color: #cbd5e1;
        }

        .value {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }

        .small {
            color: #94a3b8;
            font-size: 13px;
        }

        .healthy {
            color: #4ade80;
        }

        .failed {
            color: #f87171;
        }

        .progress {
            height: 10px;
            background: #334155;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 15px;
        }

        .progress-bar {
            height: 100%;
            background: #38bdf8;
        }

        .info {
            margin-top: 25px;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 22px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #334155;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .updated {
            text-align: center;
            color: #64748b;
            margin-top: 25px;
            font-size: 13px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>🖥️ System Resource Health</h1>

    <div class="subtitle">
        Laravel application and server resource monitoring
    </div>

    <div class="navigation">

        <a href="/health">
            🏠 Current Health
        </a>

        <a href="/health-history">
            📊 History
        </a>

        <a href="/health-incidents">
            🚨 Incidents
        </a>

        <a href="/health-system">
            🖥️ System Monitor
        </a>

    </div>

    <div class="grid">

        <div class="card">

            <h2>
                💾 Disk Usage
            </h2>

            <div class="value">
                {{ $diskUsedPercentage }}%
            </div>

            <div class="small">
                Free:
                {{ $diskFree !== false ? round($diskFree / 1024 / 1024 / 1024, 2) : 'Unknown' }}
                GB
            </div>

            <div class="progress">

                <div class="progress-bar"
                    style="width: {{ min($diskUsedPercentage, 100) }}%">
                </div>

            </div>

        </div>

        <div class="card">

            <h2>
                🧠 PHP Memory
            </h2>

            <div class="value">
                {{ round($memoryUsage / 1024 / 1024, 2) }} MB
            </div>

            <div class="small">
                Current PHP memory usage
            </div>

        </div>

        <div class="card">

            <h2>
                📈 Peak Memory
            </h2>

            <div class="value">
                {{ round($memoryPeak / 1024 / 1024, 2) }} MB
            </div>

            <div class="small">
                Peak memory used by request
            </div>

        </div>

        <div class="card">

            <h2>
                ⚡ Load Average
            </h2>

            @if($loadAverage)

                <div class="value">
                    {{ $loadAverage['1_minute'] }}
                </div>

                <div class="small">

                    1 minute:
                    {{ $loadAverage['1_minute'] }}

                    <br>

                    5 minutes:
                    {{ $loadAverage['5_minutes'] }}

                    <br>

                    15 minutes:
                    {{ $loadAverage['15_minutes'] }}

                </div>

            @else

                <div class="value">
                    N/A
                </div>

                <div class="small">
                    Load average is not available
                    on this operating system.
                </div>

            @endif

        </div>

        <div class="card">

            <h2>
                🗄️ Database
            </h2>

            <div class="value
                {{ $databaseStatus === 'Connected'
                    ? 'healthy'
                    : 'failed' }}">

                {{ $databaseStatus }}

            </div>

            <div class="small">

                @if($databaseTime !== null)

                    Response:
                    {{ $databaseTime }} ms

                @else

                    Connection failed

                @endif

            </div>

        </div>

        <div class="card">

            <h2>
                🧹 Cache
            </h2>

            <div class="value
                {{ $cacheStatus === 'Available'
                    ? 'healthy'
                    : 'failed' }}">

                {{ $cacheStatus }}

            </div>

            <div class="small">
                Cache read/write test
            </div>

        </div>

    </div>

    <div class="info">

        <h2>
            ⚙️ Application Information
        </h2>

        <div class="info-row">

            <span>
                PHP Version
            </span>

            <strong>
                {{ PHP_VERSION }}
            </strong>

        </div>

        <div class="info-row">

            <span>
                Laravel Version
            </span>

            <strong>
                {{ app()->version() }}
            </strong>

        </div>

        <div class="info-row">

            <span>
                Application Environment
            </span>

            <strong>
                {{ app()->environment() }}
            </strong>

        </div>

        <div class="info-row">

            <span>
                Storage
            </span>

            <strong class="{{ $storageStatus === 'Available'
                ? 'healthy'
                : 'failed' }}">

                {{ $storageStatus }}

            </strong>

        </div>

    </div>

    <div class="updated"
        id="updated">

        Page loaded:
        {{ now()->format('d M Y, h:i:s A') }}

    </div>

</div>

<script>

function updateSystemData()
{
    fetch('/health-system-json')
        .then(response => response.json())
        .then(data => {

            document.getElementById('updated')
                .innerText =
                'Last updated: ' +
                data.timestamp;

        })
        .catch(error => {

            console.error(
                'System monitoring error:',
                error
            );

        });
}

setInterval(updateSystemData, 5000);

</script>

</body>

</html>