<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Laravel Health Dashboard</title>

    {{-- Bootstrap 5 --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #ffffff;
            padding: 30px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #94a3b8;
            margin-bottom: 25px;
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
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid #334155;
        }

        .navigation a:hover {
            background: #334155;
        }

        .toolbar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
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

        .toolbar button:hover {
            opacity: .85;
        }

        .btn-green {
            background: #16a34a !important;
        }

        .btn-orange {
            background: #d97706 !important;
        }

        .btn-red {
            background: #dc2626 !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Bootstrap Success Message Area
        |--------------------------------------------------------------------------
        */

        #actionMessage {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            width: 380px;
            max-width: calc(100% - 40px);
        }

        .custom-success-alert {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.35);
            border-radius: 10px;
        }

        .message {
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .success {
            background: #166534;
        }

        .error {
            background: #991b1b;
        }

        .overall {
            text-align: center;
            margin-bottom: 25px;
        }

        .badge {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 18px;
        }

        .badge-ok {
            background: #16a34a;
        }

        .badge-failed {
            background: #dc2626;
        }

        .score-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .score {
            font-size: 42px;
            font-weight: bold;
        }

        .counter-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(180px, 1fr));

            gap: 15px;
            margin-bottom: 25px;
        }

        .counter {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 18px;
            text-align: center;
        }

        .counter-title {
            color: #94a3b8;
            font-size: 14px;
        }

        .counter-value {
            font-size: 30px;
            font-weight: bold;
            margin-top: 8px;
        }

        .ok-number {
            color: #4ade80;
        }

        .warning-number {
            color: #fbbf24;
        }

        .failed-number {
            color: #f87171;
        }

        .filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .filters input,
        .filters select {
            flex: 1;
            min-width: 220px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #475569;
            background: #1e293b;
            color: white;
        }

        .card {
            background: #1e293b;
            border: 1px solid #334155;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;

            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .card strong {
            font-size: 17px;
        }

        .summary {
            color: #94a3b8;
            margin-top: 7px;
            font-size: 14px;
        }

        .status {
            padding: 7px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 13px;
            white-space: nowrap;
        }

        .ok {
            background: #16a34a;
        }

        .failed {
            background: #dc2626;
        }

        .warning {
            background: #d97706;
        }

        .last-updated {
            text-align: center;
            color: #64748b;
            margin-top: 25px;
            font-size: 13px;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #94a3b8;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 600px) {

            body {
                padding: 15px;
            }

            #actionMessage {
                top: 10px;
                right: 10px;
                width: calc(100% - 20px);
                max-width: none;
            }

            .card {
                flex-direction: column;
                align-items: flex-start;
            }

        }
    </style>

</head>

<body>

    {{-- Bootstrap JavaScript Success Message Container --}}
    <div id="actionMessage"></div>


    <div class="container">

        <h1>💙 Laravel Health Dashboard</h1>

        <div class="subtitle">
            Real-time application health monitoring
        </div>


        {{-- Navigation --}}

        <div class="navigation">

            <a href="/health">
                🏠 Current Health
            </a>

            <a href="/health-history">
                📊 Health History
            </a>

            <a href="/health-incidents">
                🚨 Incidents
            </a>

            <a href="/health-system">
                🖥️ System Monitor
            </a>

            <a href="/health-dashboard">
                ⚙️ Spatie Dashboard
            </a>

            <a
                href="/health-json"
                target="_blank">

                🔗 JSON

            </a>

        </div>


        {{-- Laravel Session Success Message --}}

        @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
            role="alert">

            <strong>✅ Success!</strong>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

        @endif


        {{-- Laravel Session Error Message --}}

        @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
            role="alert">

            <strong>❌ Error!</strong>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

        @endif


        {{-- Toolbar --}}

        <div class="toolbar">

            <form
                method="POST"
                action="{{ route('health.run') }}"
                onsubmit="showSuccessMessage(
                    'Health check started successfully.'
                )">

                @csrf

                <button
                    type="submit"
                    class="btn-green">

                    ⚡ Run Health Check Now

                </button>

            </form>


            <button
                type="button"
                onclick="manualRefresh()">

                🔄 Refresh Now

            </button>


            <button
                type="button"
                id="autoRefreshButton"
                class="btn-orange"
                onclick="toggleAutoRefresh()">

                ⏸️ Pause Auto Refresh

            </button>

        </div>


        {{-- Overall Status --}}

        <div
            id="overall"
            class="overall">

            <div class="loading">
                Checking application health...
            </div>

        </div>


        {{-- Health Score --}}

        <div class="score-card">

            <div>
                📊 Current Health Score
            </div>

            <div
                id="healthScore"
                class="score">

                --

            </div>

            <div
                id="healthScoreText"
                class="summary">

                Waiting for health data...

            </div>

        </div>


        {{-- Counters --}}

        <div class="counter-grid">

            <div class="counter">

                <div class="counter-title">
                    Total Checks
                </div>

                <div
                    id="totalCount"
                    class="counter-value">

                    0

                </div>

            </div>


            <div class="counter">

                <div class="counter-title">
                    Healthy
                </div>

                <div
                    id="okCount"
                    class="counter-value ok-number">

                    0

                </div>

            </div>


            <div class="counter">

                <div class="counter-title">
                    Warnings
                </div>

                <div
                    id="warningCount"
                    class="counter-value warning-number">

                    0

                </div>

            </div>


            <div class="counter">

                <div class="counter-title">
                    Failed
                </div>

                <div
                    id="failedCount"
                    class="counter-value failed-number">

                    0

                </div>

            </div>

        </div>


        {{-- Search and filter --}}

        <div class="filters">

            <input
                type="text"
                id="healthSearch"
                placeholder="🔎 Search health checks...">


            <select id="healthStatus">

                <option value="all">
                    All Statuses
                </option>

                <option value="ok">
                    OK
                </option>

                <option value="warning">
                    Warning
                </option>

                <option value="failed">
                    Failed
                </option>

            </select>

        </div>


        {{-- Results --}}

        <div id="results">

            <div class="loading">
                Loading health checks...
            </div>

        </div>


        <div
            id="lastUpdated"
            class="last-updated">

        </div>

    </div>


    {{-- Bootstrap JS --}}

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


    <script>

        let healthResults = [];

        let autoRefreshEnabled = true;

        let refreshTimer = null;


        /*
        |--------------------------------------------------------------------------
        | Bootstrap Success Message
        |--------------------------------------------------------------------------
        */

        function showSuccessMessage(message) {

            const container =
                document.getElementById(
                    'actionMessage'
                );


            const alert =
                document.createElement('div');


            alert.className =
                'alert alert-success alert-dismissible fade show custom-success-alert';


            alert.setAttribute(
                'role',
                'alert'
            );


            alert.innerHTML = `

                <strong>✅ Success!</strong>

                ${escapeHtml(message)}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            `;


            container.innerHTML = '';

            container.appendChild(alert);


            /*
            |--------------------------------------------------------------------------
            | Automatically hide after 3 seconds
            |--------------------------------------------------------------------------
            */

            setTimeout(() => {

                const bootstrapAlert =
                    bootstrap.Alert.getOrCreateInstance(
                        alert
                    );

                bootstrapAlert.close();

            }, 3000);

        }


        /*
        |--------------------------------------------------------------------------
        | Manual Refresh
        |--------------------------------------------------------------------------
        */

        function manualRefresh() {

            showSuccessMessage(
                'Health dashboard refreshed successfully.'
            );

            loadHealth();

        }


        /*
        |--------------------------------------------------------------------------
        | Load Health
        |--------------------------------------------------------------------------
        */

        function loadHealth() {

            fetch('/health-json')

                .then(response => {

                    if (!response.ok) {

                        throw new Error(
                            'Health endpoint failed'
                        );

                    }

                    return response.json();

                })

                .then(data => {

                    healthResults =
                        data.checkResults || [];

                    renderHealth();

                })

                .catch(error => {

                    document.getElementById(
                        'overall'
                    ).innerHTML = `

                        <span class="badge badge-failed">

                            ❌ HEALTH API UNAVAILABLE

                        </span>

                    `;


                    document.getElementById(
                        'results'
                    ).innerHTML = `

                        <div class="card">

                            <div>

                                Unable to retrieve
                                health check results.

                            </div>

                        </div>

                    `;


                    showErrorMessage(
                        'Unable to retrieve health data.'
                    );


                    console.error(error);

                });

        }


        /*
        |--------------------------------------------------------------------------
        | Error Message
        |--------------------------------------------------------------------------
        */

        function showErrorMessage(message) {

            const container =
                document.getElementById(
                    'actionMessage'
                );


            const alert =
                document.createElement('div');


            alert.className =
                'alert alert-danger alert-dismissible fade show custom-success-alert';


            alert.setAttribute(
                'role',
                'alert'
            );


            alert.innerHTML = `

                <strong>❌ Error!</strong>

                ${escapeHtml(message)}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            `;


            container.innerHTML = '';

            container.appendChild(alert);


            setTimeout(() => {

                const bootstrapAlert =
                    bootstrap.Alert.getOrCreateInstance(
                        alert
                    );

                bootstrapAlert.close();

            }, 4000);

        }


        /*
        |--------------------------------------------------------------------------
        | Render Health
        |--------------------------------------------------------------------------
        */

        function renderHealth() {

            const search =
                document.getElementById('healthSearch')
                    .value
                    .toLowerCase()
                    .trim();


            const status =
                document.getElementById('healthStatus')
                    .value;


            let filtered =
                healthResults.filter(result => {

                    const label =
                        String(
                            result.label ?? ''
                        ).toLowerCase();


                    const summary =
                        String(
                            result.shortSummary ?? ''
                        ).toLowerCase();


                    const resultStatus =
                        String(
                            result.status ?? ''
                        ).toLowerCase();


                    const matchesSearch =
                        label.includes(search) ||
                        summary.includes(search);


                    const matchesStatus =
                        status === 'all' ||
                        resultStatus === status;


                    return matchesSearch &&
                        matchesStatus;

                });


            updateCounters();

            updateScore();

            updateOverall();

            renderResults(filtered);


            document.getElementById(
                'lastUpdated'
            ).innerText =
                'Last updated: ' +
                new Date().toLocaleString();

        }


        /*
        |--------------------------------------------------------------------------
        | Counters
        |--------------------------------------------------------------------------
        */

        function updateCounters() {

            const total =
                healthResults.length;


            const ok =
                healthResults.filter(
                    result =>
                    result.status === 'ok'
                ).length;


            const failed =
                healthResults.filter(
                    result =>
                    result.status === 'failed'
                ).length;


            const warning =
                total - ok - failed;


            document.getElementById(
                'totalCount'
            ).innerText = total;


            document.getElementById(
                'okCount'
            ).innerText = ok;


            document.getElementById(
                'warningCount'
            ).innerText = warning;


            document.getElementById(
                'failedCount'
            ).innerText = failed;

        }


        /*
        |--------------------------------------------------------------------------
        | Health Score
        |--------------------------------------------------------------------------
        */

        function updateScore() {

            const total =
                healthResults.length;


            if (total === 0) {

                document.getElementById(
                    'healthScore'
                ).innerText = '--';

                return;

            }


            const ok =
                healthResults.filter(
                    result =>
                    result.status === 'ok'
                ).length;


            const score =
                Math.round(
                    (ok / total) * 100
                );


            document.getElementById(
                'healthScore'
            ).innerText =
                score + '%';


            let text =
                'Application health score';


            if (score === 100) {

                text =
                    'Excellent - all health checks are OK.';

            } else if (score >= 80) {

                text =
                    'Good - some checks need attention.';

            } else {

                text =
                    'Attention required - one or more checks are unhealthy.';

            }


            document.getElementById(
                'healthScoreText'
            ).innerText = text;

        }


        /*
        |--------------------------------------------------------------------------
        | Overall Status
        |--------------------------------------------------------------------------
        */

        function updateOverall() {

            const hasFailure =
                healthResults.some(
                    result =>
                    result.status === 'failed'
                );


            const hasWarning =
                healthResults.some(
                    result =>
                    result.status !== 'ok' &&
                    result.status !== 'failed'
                );


            let html;


            if (hasFailure) {

                html = `

                    <span class="badge badge-failed">

                        ❌ HEALTH CHECK FAILED

                    </span>

                `;

            } else if (hasWarning) {

                html = `

                    <span
                        class="badge"
                        style="background:#d97706">

                        ⚠️ HEALTH CHECK WARNING

                    </span>

                `;

            } else {

                html = `

                    <span class="badge badge-ok">

                        ✅ ALL SYSTEMS HEALTHY

                    </span>

                `;

            }


            document.getElementById(
                'overall'
            ).innerHTML = html;

        }


        /*
        |--------------------------------------------------------------------------
        | Render Results
        |--------------------------------------------------------------------------
        */

        function renderResults(results) {

            const container =
                document.getElementById(
                    'results'
                );


            container.innerHTML = '';


            if (results.length === 0) {

                container.innerHTML = `

                    <div class="card">

                        <div>

                            No health checks match
                            your search/filter.

                        </div>

                    </div>

                `;

                return;

            }


            results.forEach(result => {

                let statusClass =
                    'warning';


                if (result.status === 'ok') {

                    statusClass = 'ok';

                }


                if (result.status === 'failed') {

                    statusClass = 'failed';

                }


                const card =
                    document.createElement('div');


                card.className = 'card';


                card.innerHTML = `

                    <div>

                        <strong>

                            ${escapeHtml(
                                result.label ??
                                'Unknown Check'
                            )}

                        </strong>


                        <div class="summary">

                            ${escapeHtml(
                                result.shortSummary ??
                                'No summary available'
                            )}

                        </div>

                    </div>


                    <div class="status ${statusClass}">

                        ${String(
                            result.status ??
                            'unknown'
                        ).toUpperCase()}

                    </div>

                `;


                container.appendChild(card);

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            'healthSearch'
        ).addEventListener(
            'input',
            renderHealth
        );


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            'healthStatus'
        ).addEventListener(
            'change',
            renderHealth
        );


        /*
        |--------------------------------------------------------------------------
        | Auto Refresh
        |--------------------------------------------------------------------------
        */

        function startAutoRefresh() {

            if (refreshTimer) {

                clearInterval(
                    refreshTimer
                );

            }


            refreshTimer =
                setInterval(
                    () => {

                        if (autoRefreshEnabled) {

                            loadHealth();

                        }

                    },
                    5000
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Pause / Resume
        |--------------------------------------------------------------------------
        */

        function toggleAutoRefresh() {

            autoRefreshEnabled =
                !autoRefreshEnabled;


            const button =
                document.getElementById(
                    'autoRefreshButton'
                );


            if (autoRefreshEnabled) {

                button.innerText =
                    '⏸️ Pause Auto Refresh';


                button.className =
                    'btn-orange';


                startAutoRefresh();


                showSuccessMessage(
                    'Auto refresh resumed successfully.'
                );

            } else {

                clearInterval(
                    refreshTimer
                );


                refreshTimer = null;


                button.innerText =
                    '▶️ Resume Auto Refresh';


                button.className =
                    'btn-green';


                showSuccessMessage(
                    'Auto refresh paused successfully.'
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Escape HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value) {

            return String(value)

                .replaceAll(
                    '&',
                    '&amp;'
                )

                .replaceAll(
                    '<',
                    '&lt;'
                )

                .replaceAll(
                    '>',
                    '&gt;'
                )

                .replaceAll(
                    '"',
                    '&quot;'
                )

                .replaceAll(
                    "'",
                    '&#039;'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Initial Load
        |--------------------------------------------------------------------------
        */

        loadHealth();

        startAutoRefresh();

    </script>

</body>

</html>