<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Health History & Statistics</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            color: #0f172a;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            margin-bottom: 5px;
        }

        .subtitle {
            color: #64748b;
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
            background: #0f172a;
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

        .filter-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(200px, 1fr));

            gap: 10px;
        }

        input,
        select,
        button {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }

        button {
            background: #0f172a;
            color: white;
            cursor: pointer;
        }

        .export {
            display: inline-block;
            text-decoration: none;
            background: #16a34a;
            color: white;
            padding: 10px 15px;
            border-radius: 7px;
            margin-top: 12px;
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
        }

        .stat-title {
            color: #64748b;
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
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: #f8fafc;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .ok {
            background: #dcfce7;
            color: #166534;
        }

        .failed {
            background: #fee2e2;
            color: #991b1b;
        }

        .warning {
            background: #fef3c7;
            color: #92400e;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 7px 11px;
            margin-right: 4px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            text-decoration: none;
            color: #0f172a;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #64748b;
        }
    </style>

</head>

<body>

    <div class="container">

        <h1>📊 Health History & Statistics</h1>

        <div class="subtitle">
            Historical application health monitoring
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




        {{-- Statistics --}}

        <div class="stats">

            <div class="stat">

                <div class="stat-title">
                    Total Checks
                </div>

                <div class="stat-value">
                    {{ $totalChecks }}
                </div>

            </div>


            <div class="stat">

                <div class="stat-title">
                    Successful Checks
                </div>

                <div class="stat-value">
                    {{ $successfulChecks }}
                </div>

            </div>


            <div class="stat">

                <div class="stat-title">
                    Failed Checks
                </div>

                <div class="stat-value">
                    {{ $failedChecks }}
                </div>

            </div>


            <div class="stat">

                <div class="stat-title">
                    Warnings
                </div>

                <div class="stat-value">
                    {{ $warningChecks }}
                </div>

            </div>


            <div class="stat">

                <div class="stat-title">
                    Success Rate
                </div>

                <div class="stat-value">
                    {{ $successRate }}%
                </div>

            </div>


            <div class="stat">

                <div class="stat-title">
                    Failure Rate
                </div>

                <div class="stat-value">
                    {{ $failureRate }}%
                </div>

            </div>

        </div>


        {{-- Check Statistics --}}

        <div class="section">

            <h2>
                📈 Check Statistics
            </h2>

            <table>

                <thead>

                    <tr>

                        <th>
                            Health Check
                        </th>

                        <th>
                            Total
                        </th>

                        <th>
                            Successful
                        </th>

                        <th>
                            Failed
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($checkStatistics as $stat)

                    <tr>

                        <td>
                            {{ $stat->check_label }}
                        </td>

                        <td>
                            {{ $stat->total }}
                        </td>

                        <td>
                            {{ $stat->successful }}
                        </td>

                        <td>
                            {{ $stat->failed }}
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="4"
                            class="empty">

                            No health history available.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Filters --}}

        <div class="filter">

            <form
                method="GET"
                action="{{ route('health.history') }}">

                <div class="filter-grid">

                    <div>

                        <label>
                            Period
                        </label>

                        <select name="days">

                            @foreach([1, 3, 5, 7, 30] as $period)

                            <option
                                value="{{ $period }}"
                                {{ $days == $period ? 'selected' : '' }}>

                                Last {{ $period }} Day{{ $period > 1 ? 's' : '' }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    <div>

                        <label>
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search checks...">

                    </div>


                    <div>

                        <label>
                            Status
                        </label>

                        <select name="status">

                            <option
                                value="all"
                                {{ $status === 'all' ? 'selected' : '' }}>

                                All

                            </option>

                            <option
                                value="ok"
                                {{ $status === 'ok' ? 'selected' : '' }}>

                                OK

                            </option>

                            <option
                                value="failed"
                                {{ $status === 'failed' ? 'selected' : '' }}>

                                Failed

                            </option>

                            <option
                                value="warning"
                                {{ $status === 'warning' ? 'selected' : '' }}>

                                Warning

                            </option>

                        </select>

                    </div>


                    <div>

                        <label>
                            &nbsp;
                        </label>

                        <button type="submit">
                            🔎 Apply Filters
                        </button>

                    </div>

                </div>

            </form>


            <a
                class="export"
                href="{{ route('health.history.export', ['days' => $days]) }}">

                📥 Export History CSV

            </a>

        </div>



        {{-- History --}}

        <div class="section">

            <h2>
                🕒 Health Check History
            </h2>

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
                            Completed
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($history as $item)

                    @php

                    $statusClass = 'warning';

                    if ($item->status === 'ok') {
                    $statusClass = 'ok';
                    }

                    if ($item->status === 'failed') {
                    $statusClass = 'failed';
                    }

                    @endphp


                    <tr>

                        <td>
                            {{ $item->check_label }}
                        </td>

                        <td>

                            <span
                                class="status {{ $statusClass }}">

                                {{ strtoupper($item->status) }}

                            </span>

                        </td>

                        <td>
                            {{ $item->short_summary ?? '-' }}
                        </td>

                        <td>
                            {{ $item->ended_at }}
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="4"
                            class="empty">

                            No health check history found.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>


            <div class="pagination">

                {{ $history->links() }}

            </div>

        </div>

    </div>

</body>

</html>