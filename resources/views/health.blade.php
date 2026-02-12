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
