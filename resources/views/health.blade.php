<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Laravel Health Dashboard</title>

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
            margin-bottom: 30px;
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

        .overall {
            text-align: center;
            margin-bottom: 30px;
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

    </style>
</head>

<body>

<div class="container">

    <h1>💙 Laravel Health Dashboard</h1>

    <div class="subtitle">
        Real-time application health monitoring
    </div>

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

        <a href="/health-json" target="_blank">
            🔗 JSON
        </a>

    </div>

    <div id="overall" class="overall">
        <div class="loading">
            Checking application health...
        </div>
    </div>

    <div id="results"></div>

    <div id="lastUpdated"
        class="last-updated">
    </div>

</div>

<script>

function loadHealth() {

    fetch('/health-json')
        .then(response => {

            if (!response.ok) {
                throw new Error('Health endpoint failed');
            }

            return response.json();
        })
        .then(data => {

            let results = data.checkResults || [];

            let hasFailure = results.some(
                result => result.status !== 'ok'
            );

            let overall = document.getElementById('overall');

            overall.innerHTML = `
                <span class="badge ${hasFailure ? 'badge-failed' : 'badge-ok'}">
                    ${hasFailure ? '❌ HEALTH CHECK FAILED' : '✅ ALL SYSTEMS HEALTHY'}
                </span>
            `;

            let container =
                document.getElementById('results');

            container.innerHTML = '';

            if (results.length === 0) {

                container.innerHTML = `
                    <div class="loading">
                        No health check results available.
                    </div>
                `;

                return;
            }

            results.forEach(result => {

                let statusClass = 'warning';

                if (result.status === 'ok') {
                    statusClass = 'ok';
                }

                if (result.status === 'failed') {
                    statusClass = 'failed';
                }

                container.innerHTML += `

                    <div class="card">

                        <div>

                            <strong>
                                ${result.label}
                            </strong>

                            <div class="summary">
                                ${result.shortSummary ?? 'No summary available'}
                            </div>

                        </div>

                        <div class="status ${statusClass}">
                            ${result.status.toUpperCase()}
                        </div>

                    </div>

                `;
            });

            document.getElementById('lastUpdated')
                .innerText =
                'Last updated: ' +
                new Date().toLocaleString();

        })
        .catch(error => {

            document.getElementById('overall')
                .innerHTML = `
                    <span class="badge badge-failed">
                        ❌ HEALTH API UNAVAILABLE
                    </span>
                `;

            document.getElementById('results')
                .innerHTML = `
                    <div class="card">
                        <div>
                            Unable to retrieve health check results.
                        </div>
                    </div>
                `;

            console.error(error);
        });
}

loadHealth();

setInterval(loadHealth, 5000);

</script>

</body>

</html>