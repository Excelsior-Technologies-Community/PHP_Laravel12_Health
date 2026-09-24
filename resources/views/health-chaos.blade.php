<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Synthetic Health Stress-Tester & Chaos Simulator</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #f8fafc; padding: 25px; }
        .container { max-width: 1250px; margin: auto; }
        h1 { text-align: center; margin-bottom: 5px; color: #f1f5f9; font-size: 26px; }
        .subtitle { text-align: center; color: #94a3b8; margin-bottom: 25px; font-size: 14px; }
        .navigation { display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; margin-bottom: 25px; }
        .navigation a { text-decoration: none; color: #cbd5e1; background: #1e293b; padding: 9px 16px; border-radius: 8px; border: 1px solid #334155; font-size: 13.5px; }
        .navigation a:hover, .navigation a.active { background: #334155; color: white; }
        .alert-success { background: #064e3b; color: #a7f3d0; border: 1px solid #047857; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .card { background: #1e293b; border: 1px solid #334155; padding: 25px; border-radius: 12px; margin-bottom: 25px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .btn { display: inline-block; padding: 12px 20px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer; color: white; text-decoration: none; font-size: 14px; width: 100%; text-align: center; }
        .btn-red { background: #dc2626; }
        .btn-red:hover { background: #b91c1c; }
        .btn-green { background: #16a34a; }
        .btn-green:hover { background: #15803d; }
        .btn-orange { background: #d97706; }
        .btn-purple { background: #7c3aed; }
        .status-pill { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-active { background: #7f1d1d; color: #fca5a5; }
        .status-healthy { background: #064e3b; color: #a7f3d0; }
        @media (max-width: 900px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <div class="container">
        <h1>⚡ Synthetic Health Stress-Tester & Chaos Simulator</h1>
        <div class="subtitle">1-Click Chaos Control Studio to simulate system stress, DB latency surges, memory spikes, and verify Spatie Health responsiveness.</div>

        <div class="navigation">
            <a href="{{ route('health') }}">Dashboard</a>
            <a href="{{ route('health.spatie') }}">Spatie View</a>
            <a href="{{ route('health.history') }}">History</a>
            <a href="{{ route('health.incidents') }}">Incidents</a>
            <a href="{{ route('health.system') }}">System</a>
            <a href="{{ route('health.logs') }}">Log Inspector</a>
            <a href="{{ route('health.chaos') }}" class="active">Chaos Simulator</a>
            <a href="{{ route('health.gauges') }}">Resource Gauges</a>
        </div>

        @if(session('success'))
            <div class="alert-success">✓ {{ session('success') }}</div>
        @endif

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h2 style="margin: 0 0 6px; font-size: 20px;">Chaos Simulation Protection Status</h2>
                    <p style="margin: 0; color: #94a3b8; font-size: 14px;">
                        @if($hasActiveChaos)
                            Status: <span class="status-pill status-active">💥 SYNTHETIC CHAOS ACTIVE</span>
                        @else
                            Status: <span class="status-pill status-healthy">🟢 BASELINE HEALTHY</span>
                        @endif
                    </p>
                </div>

                @if($hasActiveChaos)
                    <form action="{{ route('health.chaos.reset') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-green" style="width: auto;">🟢 Auto-Recover / Reset All Scenarios</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid-2">

            <!-- DB Latency Surge -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <h3 style="margin: 0; color: #f8fafc; font-size: 17px;">💥 DB Latency Surge Simulation</h3>
                    @if($dbSurge)
                        <span class="status-pill status-active">SURGE ACTIVE</span>
                    @else
                        <span class="status-pill status-healthy">NORMAL (1.2ms)</span>
                    @endif
                </div>
                <p style="color: #94a3b8; font-size: 13.5px; line-height: 1.5; margin-bottom: 18px;">
                    Simulates a 5000ms database query response latency delay. Triggers database connection warning in Spatie Health checks.
                </p>
                <form action="{{ route('health.chaos.trigger') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="db_surge">
                    @if($dbSurge)
                        <button type="submit" class="btn btn-green">Restore Normal DB Latency</button>
                    @else
                        <button type="submit" class="btn btn-red">💥 Trigger DB Latency Surge (5000ms)</button>
                    @endif
                </form>
            </div>

            <!-- Memory Spike -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <h3 style="margin: 0; color: #f8fafc; font-size: 17px;">⚠️ High Memory Spike Simulation</h3>
                    @if($memorySpike)
                        <span class="status-pill status-active">SPIKE ACTIVE (96.8%)</span>
                    @else
                        <span class="status-pill status-healthy">NORMAL (22.4%)</span>
                    @endif
                </div>
                <p style="color: #94a3b8; font-size: 13.5px; line-height: 1.5; margin-bottom: 18px;">
                    Simulates 96.8% memory usage exhaustion threshold. Triggers Critical Memory Warning alert payload.
                </p>
                <form action="{{ route('health.chaos.trigger') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="memory_spike">
                    @if($memorySpike)
                        <button type="submit" class="btn btn-green">Restore Memory Baseline</button>
                    @else
                        <button type="submit" class="btn btn-orange">⚠️ Trigger Memory Spike (96.8%)</button>
                    @endif
                </form>
            </div>

            <!-- Disk Space Low -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <h3 style="margin: 0; color: #f8fafc; font-size: 17px;">💾 Low Disk Space Threshold</h3>
                    @if($diskLow)
                        <span class="status-pill status-active">DISK LOW (98.2%)</span>
                    @else
                        <span class="status-pill status-healthy">NORMAL (42.0%)</span>
                    @endif
                </div>
                <p style="color: #94a3b8; font-size: 13.5px; line-height: 1.5; margin-bottom: 18px;">
                    Simulates disk storage partition filling up to 98.2% capacity. Verifies Spatie UsedDiskSpaceCheck.
                </p>
                <form action="{{ route('health.chaos.trigger') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="disk_low">
                    @if($diskLow)
                        <button type="submit" class="btn btn-green">Restore Disk Storage</button>
                    @else
                        <button type="submit" class="btn btn-purple">💾 Trigger Low Disk Space (98.2%)</button>
                    @endif
                </form>
            </div>

            <!-- High CPU Load -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <h3 style="margin: 0; color: #f8fafc; font-size: 17px;">🔥 High CPU Load Surge</h3>
                    @if($cpuHigh)
                        <span class="status-pill status-active">CPU SURGE (94.5%)</span>
                    @else
                        <span class="status-pill status-healthy">NORMAL (18.5%)</span>
                    @endif
                </div>
                <p style="color: #94a3b8; font-size: 13.5px; line-height: 1.5; margin-bottom: 18px;">
                    Simulates heavy background worker queue CPU load. Verifies CPU throttling diagnostics.
                </p>
                <form action="{{ route('health.chaos.trigger') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="cpu_high">
                    @if($cpuHigh)
                        <button type="submit" class="btn btn-green">Restore CPU Load</button>
                    @else
                        <button type="submit" class="btn btn-red">🔥 Trigger High CPU Load (94.5%)</button>
                    @endif
                </form>
            </div>

        </div>
    </div>

</body>
</html>
