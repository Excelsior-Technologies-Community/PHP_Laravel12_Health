<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
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
            margin-bottom: 20px;
        }

        .navigation a {
            text-decoration: none;
            color: white;
            background: #1e293b;
            padding: 10px 15px;
            border-radius: 7px;
            border: 1px solid #334155;
        }

        .toolbar {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .toolbar button {
            border: 0;
            padding: 11px 16px;
            border-radius: 8px;
            cursor: pointer;
            color: white;
            background: #2563eb;
            font-weight: bold;
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

        .warning {
            color: #fbbf24;
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
            width: 0;
            transition: width .3s;
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

        <h1>
            🖥️ System Resource Health
        </h1>

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


        <div class="toolbar">

            <button onclick="updateSystemData()">
                🔄 Refresh Now
            </button>

            <button onclick="toggleSystemRefresh()"
                id="systemRefreshButton">

                ⏸️ Pause Auto Refresh

            </button>

        </div>


        <div class="grid">


            {{-- Disk --}}

            <div class="card">

                <h2>
                    💾 Disk Usage
                </h2>

                <div
                    class="value"
                    id="diskUsage">

                    {{ $diskUsedPercentage }}%

                </div>

                <div
                    class="small"
                    id="diskFree">

                    Free:
                    {{ $diskFree !== false
                    ? round(
                        $diskFree / 1024 / 1024 / 1024,
                        2
                    )
                    : 'Unknown'
                }}
                    GB

                </div>

                <div class="progress">

                    <div
                        class="progress-bar"
                        id="diskBar"
                        style="
                        width:
                        {{ min($diskUsedPercentage, 100) }}%
                    ">

                    </div>

                </div>

            </div>


            {{-- Memory --}}

            <div class="card">

                <h2>
                    🧠 PHP Memory
                </h2>

                <div
                    class="value"
                    id="memoryCurrent">

                    {{ round(
                    $memoryUsage / 1024 / 1024,
                    2
                ) }}
                    MB

                </div>

                <div class="small">
                    Current PHP memory usage
                </div>

            </div>


            {{-- Peak Memory --}}

            <div class="card">

                <h2>
                    📈 Peak Memory
                </h2>

                <div
                    class="value"
                    id="memoryPeak">

                    {{ round(
                    $memoryPeak / 1024 / 1024,
                    2
                ) }}
                    MB

                </div>

                <div class="small">
                    Peak memory used by request
                </div>

            </div>


            {{-- Load --}}

            <div class="card">

                <h2>
                    ⚡ Load Average
                </h2>

                <div
                    class="value"
                    id="loadAverage">

                    @if($loadAverage)
                    {{ $loadAverage['1_minute'] }}
                    @else
                    N/A
                    @endif

                </div>

                <div
                    class="small"
                    id="loadDetails">

                    @if($loadAverage)

                    1 minute:
                    {{ $loadAverage['1_minute'] }}

                    <br>

                    5 minutes:
                    {{ $loadAverage['5_minutes'] }}

                    <br>

                    15 minutes:
                    {{ $loadAverage['15_minutes'] }}

                    @else

                    Load average is not available
                    on this operating system.

                    @endif

                </div>

            </div>


            {{-- Database --}}

            <div class="card">

                <h2>
                    🗄️ Database
                </h2>

                <div
                    id="databaseStatus"
                    class="value healthy">

                    {{ $databaseStatus }}

                </div>

                <div
                    id="databaseTime"
                    class="small">

                    @if($databaseTime !== null)

                    Response:
                    {{ $databaseTime }} ms

                    @else

                    Connection failed

                    @endif

                </div>

            </div>


            {{-- Cache --}}

            <div class="card">

                <h2>
                    🧹 Cache
                </h2>

                <div
                    id="cacheStatus"
                    class="value healthy">

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

                <strong
                    id="storageStatus"
                    class="healthy">

                    {{ $storageStatus }}

                </strong>

            </div>

        </div>


        <div
            class="updated"
            id="updated">

            Page loaded:
            {{ now()->format('d M Y, h:i:s A') }}

        </div>

    </div>


    <script>
        let systemAutoRefresh = true;

        let systemTimer = null;


        /*
        |--------------------------------------------------------------------------
        | Update System Data
        |--------------------------------------------------------------------------
        */

        function updateSystemData() {
            fetch('/health-system-json')
                .then(response => {

                    if (!response.ok) {
                        throw new Error(
                            'System endpoint failed'
                        );
                    }

                    return response.json();

                })
                .then(data => {


                    /*
                    |--------------------------------------------------------------------------
                    | Memory
                    |--------------------------------------------------------------------------
                    */

                    document.getElementById(
                            'memoryCurrent'
                        ).innerText =
                        data.memory.current;


                    document.getElementById(
                            'memoryPeak'
                        ).innerText =
                        data.memory.peak;


                    /*
                    |--------------------------------------------------------------------------
                    | Disk
                    |--------------------------------------------------------------------------
                    */

                    document.getElementById(
                            'diskUsage'
                        ).innerText =
                        data.disk.used_percentage + '%';


                    document.getElementById(
                            'diskFree'
                        ).innerText =
                        'Free: ' +
                        data.disk.free;


                    document.getElementById(
                            'diskBar'
                        ).style.width =
                        Math.min(
                            data.disk.used_percentage,
                            100
                        ) + '%';


                    /*
                    |--------------------------------------------------------------------------
                    | Load Average
                    |--------------------------------------------------------------------------
                    */

                    if (data.load_average) {

                        document.getElementById(
                                'loadAverage'
                            ).innerText =
                            data.load_average['1_minute'];


                        document.getElementById(
                                'loadDetails'
                            ).innerHTML =

                            '1 minute: ' +
                            data.load_average['1_minute'] +

                            '<br>' +

                            '5 minutes: ' +
                            data.load_average['5_minutes'] +

                            '<br>' +

                            '15 minutes: ' +
                            data.load_average['15_minutes'];

                    } else {

                        document.getElementById(
                            'loadAverage'
                        ).innerText = 'N/A';

                        document.getElementById(
                                'loadDetails'
                            ).innerText =
                            'Load average unavailable.';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Database
                    |--------------------------------------------------------------------------
                    */

                    const databaseStatus =
                        document.getElementById(
                            'databaseStatus'
                        );


                    databaseStatus.innerText =
                        data.database.status;


                    databaseStatus.className =
                        data.database.status === 'Connected' ?
                        'value healthy' :
                        'value failed';


                    document.getElementById(
                            'databaseTime'
                        ).innerText =

                        data.database.response_ms !== null ?
                        'Response: ' +
                        data.database.response_ms +
                        ' ms' :
                        'Connection failed';


                    /*
                    |--------------------------------------------------------------------------
                    | Cache
                    |--------------------------------------------------------------------------
                    */

                    const cacheStatus =
                        document.getElementById(
                            'cacheStatus'
                        );


                    cacheStatus.innerText =
                        data.cache.status;


                    cacheStatus.className =
                        data.cache.status === 'Available' ?
                        'value healthy' :
                        'value failed';


                    /*
                    |--------------------------------------------------------------------------
                    | Storage
                    |--------------------------------------------------------------------------
                    */

                    const storageStatus =
                        document.getElementById(
                            'storageStatus'
                        );


                    storageStatus.innerText =
                        data.storage.status;


                    storageStatus.className =
                        data.storage.status === 'Available' ?
                        'healthy' :
                        'failed';


                    /*
                    |--------------------------------------------------------------------------
                    | Last Updated
                    |--------------------------------------------------------------------------
                    */

                    document.getElementById(
                            'updated'
                        ).innerText =

                        'Last updated: ' +
                        data.timestamp;

                })
                .catch(error => {

                    console.error(
                        'System monitoring error:',
                        error
                    );

                    document.getElementById(
                            'updated'
                        ).innerText =
                        'System monitoring unavailable';

                });
        }


        /*
        |--------------------------------------------------------------------------
        | Auto Refresh
        |--------------------------------------------------------------------------
        */

        function startSystemRefresh() {
            if (systemTimer) {
                clearInterval(systemTimer);
            }

            systemTimer =
                setInterval(
                    updateSystemData,
                    5000
                );
        }


        function toggleSystemRefresh() {
            systemAutoRefresh = !systemAutoRefresh;


            const button =
                document.getElementById(
                    'systemRefreshButton'
                );


            if (systemAutoRefresh) {

                button.innerText =
                    '⏸️ Pause Auto Refresh';

                startSystemRefresh();

            } else {

                clearInterval(systemTimer);

                systemTimer = null;

                button.innerText =
                    '▶️ Resume Auto Refresh';

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Initial Load
        |--------------------------------------------------------------------------
        */

        updateSystemData();

        startSystemRefresh();
    </script>

</body>

</html>