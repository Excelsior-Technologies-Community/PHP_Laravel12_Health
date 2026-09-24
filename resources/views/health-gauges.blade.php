<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Server Resource Gauges & Performance Analytics</title>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #f8fafc; padding: 25px; }
        .container { max-width: 1250px; margin: auto; }
        h1 { text-align: center; margin-bottom: 5px; color: #f1f5f9; font-size: 26px; }
        .subtitle { text-align: center; color: #94a3b8; margin-bottom: 25px; font-size: 14px; }
        .navigation { display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; margin-bottom: 25px; }
        .navigation a { text-decoration: none; color: #cbd5e1; background: #1e293b; padding: 9px 16px; border-radius: 8px; border: 1px solid #334155; font-size: 13.5px; }
        .navigation a:hover, .navigation a.active { background: #334155; color: white; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .card { background: #1e293b; border: 1px solid #334155; padding: 22px; border-radius: 12px; text-align: center; }
        .card h3 { margin-top: 0; margin-bottom: 12px; font-size: 16px; color: #38bdf8; }
        .gauge-box { max-width: 200px; margin: 0 auto; position: relative; }
        .gauge-val { font-size: 26px; font-weight: bold; margin-top: 10px; color: #f8fafc; }
        .gauge-status { font-size: 12px; font-weight: bold; margin-top: 4px; display: inline-block; padding: 3px 10px; border-radius: 20px; }
        .status-HEALTHY { background: #064e3b; color: #a7f3d0; }
        .status-WARNING { background: #78350f; color: #fde68a; }
        .status-CRITICAL { background: #7f1d1d; color: #fca5a5; }
        .pulse-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #10b981; margin-right: 6px; box-shadow: 0 0 8px #10b981; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { opacity: 0.4; } 50% { opacity: 1; } 100% { opacity: 0.4; } }
        @media (max-width: 900px) { .grid-4, .grid-2 { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 600px) { .grid-4, .grid-2 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <div class="container">
        <h1>📊 Real-Time Server Resource Gauges & Performance Analytics</h1>
        <div class="subtitle">Visual real-time speedometer gauges for Server Memory, CPU Load, Disk Storage, and Database Query Latency.</div>

        <div class="navigation">
            <a href="{{ route('health') }}">Dashboard</a>
            <a href="{{ route('health.spatie') }}">Spatie View</a>
            <a href="{{ route('health.history') }}">History</a>
            <a href="{{ route('health.incidents') }}">Incidents</a>
            <a href="{{ route('health.system') }}">System</a>
            <a href="{{ route('health.logs') }}">Log Inspector</a>
            <a href="{{ route('health.chaos') }}">Chaos Simulator</a>
            <a href="{{ route('health.gauges') }}" class="active">Resource Gauges</a>
        </div>

        <div style="background: #1e293b; border: 1px solid #334155; padding: 14px 20px; border-radius: 10px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div>
                <span class="pulse-dot"></span>
                <strong>Live Metrics Refresh Stream</strong>
                <span style="color: #94a3b8; font-size: 13px; margin-left: 10px;">Auto-polling every 3 seconds</span>
            </div>
            <div style="font-size: 13px; color: #38bdf8;">
                Last Updated: <span id="lastUpdated">Just now</span>
            </div>
        </div>

        <!-- 4 Resource Gauges -->
        <div class="grid-4">

            <!-- Memory Gauge -->
            <div class="card">
                <h3>🧠 Memory Usage</h3>
                <div class="gauge-box">
                    <canvas id="memGauge"></canvas>
                </div>
                <div class="gauge-val" id="memVal">0 %</div>
                <div class="gauge-status status-HEALTHY" id="memStatus">HEALTHY</div>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 6px;" id="memDetail">Used: 0 MB</div>
            </div>

            <!-- CPU Load Gauge -->
            <div class="card">
                <h3>⚡ CPU Load Average</h3>
                <div class="gauge-box">
                    <canvas id="cpuGauge"></canvas>
                </div>
                <div class="gauge-val" id="cpuVal">0 %</div>
                <div class="gauge-status status-HEALTHY" id="cpuStatus">HEALTHY</div>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 6px;">Load Average %</div>
            </div>

            <!-- Disk Usage Gauge -->
            <div class="card">
                <h3>💾 Disk Storage Capacity</h3>
                <div class="gauge-box">
                    <canvas id="diskGauge"></canvas>
                </div>
                <div class="gauge-val" id="diskVal">0 %</div>
                <div class="gauge-status status-HEALTHY" id="diskStatus">HEALTHY</div>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 6px;">Partition Used %</div>
            </div>

            <!-- DB Latency Gauge -->
            <div class="card">
                <h3>🗄️ DB Query Latency</h3>
                <div class="gauge-box">
                    <canvas id="dbGauge"></canvas>
                </div>
                <div class="gauge-val" id="dbVal">0 ms</div>
                <div class="gauge-status status-HEALTHY" id="dbStatus">HEALTHY</div>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 6px;">Response Speed</div>
            </div>

        </div>

        <!-- Latency & Load Analytics Chart -->
        <div class="grid-2">
            <div class="card" style="text-align: left;">
                <h3 style="text-align: center;">📈 Live Response Latency Stream (ms)</h3>
                <canvas id="latencyLineChart" style="max-height: 250px;"></canvas>
            </div>
            <div class="card" style="text-align: left;">
                <h3 style="text-align: center;">📊 Memory vs Disk Capacity Overview</h3>
                <canvas id="resourceBarChart" style="max-height: 250px;"></canvas>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function createDoughnutGauge(elementId, color) {
                const ctx = document.getElementById(elementId).getContext('2d');
                return new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [0, 100],
                            backgroundColor: [color, '#334155'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        rotation: -90,
                        circumference: 180,
                        cutout: '75%',
                        responsive: true,
                        plugins: { legend: { display: false }, tooltip: { enabled: false } }
                    }
                });
            }

            const memChart = createDoughnutGauge('memGauge', '#3b82f6');
            const cpuChart = createDoughnutGauge('cpuGauge', '#a855f7');
            const diskChart = createDoughnutGauge('diskGauge', '#10b981');
            const dbChart = createDoughnutGauge('dbGauge', '#f59e0b');

            // Line chart for Latency Stream
            const latencyCtx = document.getElementById('latencyLineChart').getContext('2d');
            const latencyLineChart = new Chart(latencyCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'DB Query Latency (ms)',
                        data: [],
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } },
                        y: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' }, beginAtZero: true }
                    },
                    plugins: { legend: { labels: { color: '#f8fafc' } } }
                }
            });

            // Bar chart for Memory vs Disk Overview
            const barCtx = document.getElementById('resourceBarChart').getContext('2d');
            const resourceBarChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['Memory Used %', 'Disk Storage Used %', 'CPU Load %'],
                    datasets: [{
                        label: 'Usage %',
                        data: [0, 0, 0],
                        backgroundColor: ['#3b82f6', '#10b981', '#a855f7'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } },
                        y: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' }, max: 100, beginAtZero: true }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            function updateGauge(chart, value, maxVal, color) {
                const val = Math.min(value, maxVal);
                chart.data.datasets[0].data = [val, maxVal - val];
                chart.data.datasets[0].backgroundColor[0] = color;
                chart.update();
            }

            function fetchLiveGauges() {
                fetch('{{ route("health.gauges.json") }}')
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('lastUpdated').innerText = data.timestamp;

                        // Memory
                        const memColor = data.memory.status === 'CRITICAL' ? '#ef4444' : (data.memory.status === 'WARNING' ? '#f59e0b' : '#3b82f6');
                        updateGauge(memChart, data.memory.percentage, 100, memColor);
                        document.getElementById('memVal').innerText = data.memory.percentage + ' %';
                        document.getElementById('memStatus').className = 'gauge-status status-' + data.memory.status;
                        document.getElementById('memStatus').innerText = data.memory.status;
                        document.getElementById('memDetail').innerText = 'Used: ' + data.memory.used_mb + ' MB';

                        // CPU
                        const cpuColor = data.cpu.status === 'CRITICAL' ? '#ef4444' : (data.cpu.status === 'WARNING' ? '#f59e0b' : '#a855f7');
                        updateGauge(cpuChart, data.cpu.load_percentage, 100, cpuColor);
                        document.getElementById('cpuVal').innerText = data.cpu.load_percentage + ' %';
                        document.getElementById('cpuStatus').className = 'gauge-status status-' + data.cpu.status;
                        document.getElementById('cpuStatus').innerText = data.cpu.status;

                        // Disk
                        const diskColor = data.disk.status === 'CRITICAL' ? '#ef4444' : (data.disk.status === 'WARNING' ? '#f59e0b' : '#10b981');
                        updateGauge(diskChart, data.disk.percentage, 100, diskColor);
                        document.getElementById('diskVal').innerText = data.disk.percentage + ' %';
                        document.getElementById('diskStatus').className = 'gauge-status status-' + data.disk.status;
                        document.getElementById('diskStatus').innerText = data.disk.status;

                        // DB Latency
                        const dbColor = data.database.status === 'CRITICAL' ? '#ef4444' : (data.database.status === 'WARNING' ? '#f59e0b' : '#10b981');
                        updateGauge(dbChart, data.database.latency_ms, 5000, dbColor);
                        document.getElementById('dbVal').innerText = data.database.latency_ms + ' ms';
                        document.getElementById('dbStatus').className = 'gauge-status status-' + data.database.status;
                        document.getElementById('dbStatus').innerText = data.database.status;

                        // Update Line Chart
                        if (latencyLineChart.data.labels.length > 10) {
                            latencyLineChart.data.labels.shift();
                            latencyLineChart.data.datasets[0].data.shift();
                        }
                        latencyLineChart.data.labels.push(data.timestamp);
                        latencyLineChart.data.datasets[0].data.push(data.database.latency_ms);
                        latencyLineChart.update();

                        // Update Bar Chart
                        resourceBarChart.data.datasets[0].data = [data.memory.percentage, data.disk.percentage, data.cpu.load_percentage];
                        resourceBarChart.update();
                    })
                    .catch(err => console.error('Error fetching gauges JSON:', err));
            }

            fetchLiveGauges();
            setInterval(fetchLiveGauges, 3000);
        });
    </script>
</body>
</html>
