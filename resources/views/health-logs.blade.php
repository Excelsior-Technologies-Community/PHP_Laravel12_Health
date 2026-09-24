<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Log Viewer & Health Diagnostics Inspector</title>
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
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 25px; }
        .stat-card { background: #1e293b; border: 1px solid #334155; padding: 20px; border-radius: 12px; }
        .stat-title { color: #94a3b8; font-size: 13px; margin-bottom: 6px; }
        .stat-number { font-size: 28px; font-weight: bold; color: #38bdf8; }
        .toolbar-card { background: #1e293b; border: 1px solid #334155; padding: 18px; border-radius: 12px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .btn { display: inline-block; padding: 9px 16px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer; color: white; text-decoration: none; font-size: 13.5px; }
        .btn-red { background: #dc2626; }
        .btn-red:hover { background: #b91c1c; }
        .btn-blue { background: #2563eb; }
        .form-control, .form-select { background: #0f172a; border: 1px solid #334155; color: white; padding: 8px 12px; border-radius: 6px; font-size: 13.5px; }
        .log-entry { background: #1e293b; border: 1px solid #334155; border-radius: 10px; margin-bottom: 12px; padding: 16px; font-family: monospace; font-size: 13px; }
        .badge-level { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; display: inline-block; }
        .badge-ERROR, .badge-CRITICAL, .badge-EMERGENCY { background: #7f1d1d; color: #fca5a5; }
        .badge-WARNING { background: #78350f; color: #fde68a; }
        .badge-INFO { background: #1e3a8a; color: #bfdbfe; }
        .badge-DEBUG { background: #334155; color: #cbd5e1; }
        .trace-box { background: #0f172a; border: 1px solid #334155; border-radius: 6px; padding: 12px; margin-top: 10px; white-space: pre-wrap; font-size: 12px; color: #cbd5e1; max-height: 250px; overflow-y: auto; }
        @media (max-width: 900px) { .stats-grid { grid-template-columns: 1fr 1fr; } }
    </style>
</head>
<body>

    <div class="container">
        <h1>📜 Real-Time Log Viewer & Diagnostics Inspector</h1>
        <div class="subtitle">Live log stream parsing, color-coded error badges, level filters, and root-cause diagnostics.</div>

        <div class="navigation">
            <a href="{{ route('health') }}">Dashboard</a>
            <a href="{{ route('health.spatie') }}">Spatie View</a>
            <a href="{{ route('health.history') }}">History</a>
            <a href="{{ route('health.incidents') }}">Incidents</a>
            <a href="{{ route('health.system') }}">System</a>
            <a href="{{ route('health.logs') }}" class="active">Log Inspector</a>
            <a href="{{ route('health.chaos') }}">Chaos Simulator</a>
            <a href="{{ route('health.gauges') }}">Resource Gauges</a>
        </div>

        @if(session('success'))
            <div class="alert-success">✓ {{ session('success') }}</div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-title">Total Logs Parsed</div>
                <div class="stat-number">{{ $totalLogs }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Errors / Critical Entries</div>
                <div class="stat-number" style="color: #ef4444;">{{ $errorLogsCount }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Warning Entries</div>
                <div class="stat-number" style="color: #f59e0b;">{{ $warningLogsCount }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Log File Status</div>
                <div class="stat-number" style="color: #10b981; font-size: 20px;">⚡ STREAM ONLINE</div>
            </div>
        </div>

        <div class="toolbar-card">
            <form action="{{ route('health.logs') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <select name="level" class="form-select" onchange="this.form.submit()">
                    <option value="ALL" {{ $filterLevel === 'ALL' ? 'selected' : '' }}>Filter by Level: ALL</option>
                    <option value="ERROR" {{ $filterLevel === 'ERROR' ? 'selected' : '' }}>🔴 ERROR</option>
                    <option value="CRITICAL" {{ $filterLevel === 'CRITICAL' ? 'selected' : '' }}>🛑 CRITICAL</option>
                    <option value="WARNING" {{ $filterLevel === 'WARNING' ? 'selected' : '' }}>🟡 WARNING</option>
                    <option value="INFO" {{ $filterLevel === 'INFO' ? 'selected' : '' }}>🔵 INFO</option>
                </select>

                <input type="text" name="search" class="form-control" placeholder="Search log message..." value="{{ $search }}" style="min-width: 240px;">
                <button type="submit" class="btn btn-blue">🔍 Search Logs</button>
            </form>

            <form action="{{ route('health.logs.clear') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-red" onclick="return confirm('Clear application log file?')">🗑 Clear Log File</button>
            </form>
        </div>

        <div style="margin-bottom: 20px;">
            <h3 style="margin-bottom: 15px; color: #f1f5f9;">Recent Parsed Application Logs (Showing latest {{ count($logs) }})</h3>

            @if(count($logs))
                @foreach($logs as $idx => $log)
                    <div class="log-entry">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                            <div>
                                <span class="badge-level badge-{{ $log['level'] }}">{{ $log['level'] }}</span>
                                <span style="color: #94a3b8; margin-left: 10px; font-size: 12px;">[{{ $log['timestamp'] }}]</span>
                                <span style="color: #64748b; margin-left: 8px; font-size: 12px;">env: {{ $log['environment'] }}</span>
                            </div>
                            @if(!empty($log['stack_trace']))
                                <button type="button" onclick="document.getElementById('trace-{{ $idx }}').classList.toggle('d-none')" class="btn btn-blue" style="padding: 3px 10px; font-size: 11px;">
                                    View Stack Trace 🔍
                                </button>
                            @endif
                        </div>

                        <div style="color: #f8fafc; word-break: break-word; font-size: 13.5px;">{{ $log['message'] }}</div>

                        @if(!empty($log['stack_trace']))
                            <div id="trace-{{ $idx }}" class="trace-box d-none">{{ $log['stack_trace'] }}</div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="stat-card" style="text-align: center; color: #94a3b8; padding: 40px;">
                    No log entries matching criteria found.
                </div>
            @endif
        </div>
    </div>

</body>
</html>
