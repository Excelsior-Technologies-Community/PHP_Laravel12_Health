<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Health Incidents</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #fff7ed;
            font-family: Arial, sans-serif;
            color: #431407;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            margin-bottom: 5px;
        }

        .subtitle {
            color: #9a3412;
            margin-bottom: 25px;
        }

        .navigation {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .navigation a {
            text-decoration: none;
            background: #431407;
            color: white;
            padding: 10px 15px;
            border-radius: 7px;
        }

        .filter {
            background: white;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        select,
        button {
            padding: 9px 12px;
            border: 1px solid #fed7aa;
            border-radius: 6px;
        }

        button {
            background: #431407;
            color: white;
            cursor: pointer;
        }

        .stats {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(200px, 1fr));

            gap: 15px;
            margin-bottom: 25px;
        }

        .stat {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }

        .stat-title {
            color: #9a3412;
            font-size: 14px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            margin-top: 8px;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #fed7aa;
        }

        th {
            background: #fff7ed;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .failed {
            background: #fee2e2;
            color: #991b1b;
        }

        .warning {
            background: #fef3c7;
            color: #92400e;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #9a3412;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 7px 11px;
            margin-right: 4px;
            border: 1px solid #fed7aa;
            border-radius: 5px;
            text-decoration: none;
            color: #431407;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>🚨 Health Incident & Failure Log</h1>

    <div class="subtitle">
        Failed and warning health checks detected by Spatie Health
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

    <div class="filter">

        <form method="GET"
            action="/health-incidents">

            <label>
                Incident Period:
            </label>

            <select name="days">

                <option value="1"
                    {{ $days == 1 ? 'selected' : '' }}>
                    Last 1 Day
                </option>

                <option value="3"
                    {{ $days == 3 ? 'selected' : '' }}>
                    Last 3 Days
                </option>

                <option value="5"
                    {{ $days == 5 ? 'selected' : '' }}>
                    Last 5 Days
                </option>

                <option value="7"
                    {{ $days == 7 ? 'selected' : '' }}>
                    Last 7 Days
                </option>

                <option value="30"
                    {{ $days == 30 ? 'selected' : '' }}>
                    Last 30 Days
                </option>

            </select>

            <button type="submit">
                Apply
            </button>

        </form>

    </div>

    <div class="stats">

        <div class="stat">

            <div class="stat-title">
                Total Incidents
            </div>

            <div class="stat-value">
                {{ $totalIncidents }}
            </div>

        </div>

        <div class="stat">

            <div class="stat-title">
                Failed Checks
            </div>

            <div class="stat-value">
                {{ $failedIncidents }}
            </div>

        </div>

        <div class="stat">

            <div class="stat-title">
                Warnings
            </div>

            <div class="stat-value">
                {{ $warningIncidents }}
            </div>

        </div>

    </div>

    <div class="section">

        <h2>🔥 Most Affected Health Checks</h2>

        <table>

            <thead>

            <tr>

                <th>
                    Health Check
                </th>

                <th>
                    Incidents
                </th>

            </tr>

            </thead>

            <tbody>

            @forelse($mostAffectedChecks as $check)

                <tr>

                    <td>
                        {{ $check->check_label }}
                    </td>

                    <td>
                        {{ $check->incidents }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="2"
                        class="empty">

                        No incidents detected.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="section">

        <h2>🚨 Incident Log</h2>

        <table>

            <thead>

            <tr>

                <th>
                    Check
                </th>

                <th>
                    Status
                </th>

                <th>
                    Summary
                </th>

                <th>
                    Notification
                </th>

                <th>
                    Time
                </th>

            </tr>

            </thead>

            <tbody>

            @forelse($incidents as $incident)

                @php

                    $statusClass =
                        $incident->status === 'failed'
                        ? 'failed'
                        : 'warning';

                @endphp

                <tr>

                    <td>
                        {{ $incident->check_label }}
                    </td>

                    <td>

                        <span class="status {{ $statusClass }}">
                            {{ strtoupper($incident->status) }}
                        </span>

                    </td>

                    <td>
                        {{ $incident->short_summary ?? '-' }}
                    </td>

                    <td>
                        {{ $incident->notification_message ?? '-' }}
                    </td>

                    <td>
                        {{ $incident->ended_at }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5"
                        class="empty">

                        ✅ No health incidents found for
                        the selected period.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

        <div class="pagination">

            {{ $incidents->links() }}

        </div>

    </div>

</div>

</body>

</html>