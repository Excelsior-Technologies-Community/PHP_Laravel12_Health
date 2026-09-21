<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Health\Models\HealthCheckResultHistoryItem;

class HealthController extends Controller
{
    /**
     * Main health dashboard.
     */
    public function index()
    {
        return view('health');
    }

    /**
     * Health history and statistics dashboard.
     */
    public function history(Request $request)
    {
        $days = (int) $request->get('days', 5);

        if (!in_array($days, [1, 3, 5, 7, 30])) {
            $days = 5;
        }

        $startDate = now()->subDays($days);

        $history = HealthCheckResultHistoryItem::query()
            ->where('ended_at', '>=', $startDate)
            ->orderByDesc('ended_at')
            ->paginate(15)
            ->withQueryString();

        $totalChecks = HealthCheckResultHistoryItem::query()
            ->where('ended_at', '>=', $startDate)
            ->count();

        $successfulChecks = HealthCheckResultHistoryItem::query()
            ->where('ended_at', '>=', $startDate)
            ->where('status', 'ok')
            ->count();

        $failedChecks = HealthCheckResultHistoryItem::query()
            ->where('ended_at', '>=', $startDate)
            ->where('status', 'failed')
            ->count();

        $warningChecks = HealthCheckResultHistoryItem::query()
            ->where('ended_at', '>=', $startDate)
            ->where('status', '!=', 'ok')
            ->where('status', '!=', 'failed')
            ->count();

        $failureRate = $totalChecks > 0
            ? round(($failedChecks / $totalChecks) * 100, 2)
            : 0;

        $successRate = $totalChecks > 0
            ? round(($successfulChecks / $totalChecks) * 100, 2)
            : 0;

        $checkStatistics = HealthCheckResultHistoryItem::query()
            ->select('check_name')
            ->selectRaw('MAX(check_label) as check_label')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'ok' THEN 1 ELSE 0 END) as successful")
            ->selectRaw("SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed")
            ->where('ended_at', '>=', $startDate)
            ->groupBy('check_name')
            ->orderByDesc('total')
            ->get();

        return view('health-history', compact(
            'history',
            'days',
            'totalChecks',
            'successfulChecks',
            'failedChecks',
            'warningChecks',
            'failureRate',
            'successRate',
            'checkStatistics'
        ));
    }

    /**
     * Health incidents and failures.
     */
    public function incidents(Request $request)
    {
        $days = (int) $request->get('days', 5);

        if (!in_array($days, [1, 3, 5, 7, 30])) {
            $days = 5;
        }

        $startDate = now()->subDays($days);

        $incidents = HealthCheckResultHistoryItem::query()
            ->where('ended_at', '>=', $startDate)
            ->where('status', '!=', 'ok')
            ->orderByDesc('ended_at')
            ->paginate(15)
            ->withQueryString();

        $totalIncidents = HealthCheckResultHistoryItem::query()
            ->where('ended_at', '>=', $startDate)
            ->where('status', '!=', 'ok')
            ->count();

        $failedIncidents = HealthCheckResultHistoryItem::query()
            ->where('ended_at', '>=', $startDate)
            ->where('status', 'failed')
            ->count();

        $warningIncidents = HealthCheckResultHistoryItem::query()
            ->where('ended_at', '>=', $startDate)
            ->where('status', '!=', 'ok')
            ->where('status', '!=', 'failed')
            ->count();

        $mostAffectedChecks = HealthCheckResultHistoryItem::query()
            ->select('check_name')
            ->selectRaw('MAX(check_label) as check_label')
            ->selectRaw('COUNT(*) as incidents')
            ->where('ended_at', '>=', $startDate)
            ->where('status', '!=', 'ok')
            ->groupBy('check_name')
            ->orderByDesc('incidents')
            ->get();

        return view('health-incidents', compact(
            'incidents',
            'days',
            'totalIncidents',
            'failedIncidents',
            'warningIncidents',
            'mostAffectedChecks'
        ));
    }

    /**
     * System and application resource health dashboard.
     */
    public function system()
    {
        $diskPath = base_path();

        $diskTotal = @disk_total_space($diskPath);
        $diskFree = @disk_free_space($diskPath);

        $diskUsed = 0;
        $diskUsedPercentage = 0;

        if ($diskTotal && $diskFree !== false) {
            $diskUsed = $diskTotal - $diskFree;
            $diskUsedPercentage = round(($diskUsed / $diskTotal) * 100, 2);
        }

        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);

        $loadAverage = null;

        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();

            if (is_array($load)) {
                $loadAverage = [
                    '1_minute' => round($load[0], 2),
                    '5_minutes' => round($load[1], 2),
                    '15_minutes' => round($load[2], 2),
                ];
            }
        }

        $databaseStatus = 'Connected';
        $databaseTime = null;

        try {
            $start = microtime(true);

            DB::connection()->getPdo();

            $databaseTime = round(
                (microtime(true) - $start) * 1000,
                2
            );
        } catch (\Throwable $exception) {
            $databaseStatus = 'Failed';
        }

        $cacheStatus = 'Available';

        try {
            Cache::put(
                'health_system_test',
                'ok',
                now()->addSeconds(10)
            );

            if (Cache::get('health_system_test') !== 'ok') {
                $cacheStatus = 'Failed';
            }
        } catch (\Throwable $exception) {
            $cacheStatus = 'Failed';
        }

        $storageStatus = File::isDirectory(storage_path())
            ? 'Available'
            : 'Unavailable';

        return view('health-system', compact(
            'diskTotal',
            'diskFree',
            'diskUsed',
            'diskUsedPercentage',
            'memoryUsage',
            'memoryPeak',
            'loadAverage',
            'databaseStatus',
            'databaseTime',
            'cacheStatus',
            'storageStatus'
        ));
    }

    /**
     * JSON endpoint for system monitoring.
     */
    public function systemJson()
    {
        $diskPath = base_path();

        $diskTotal = @disk_total_space($diskPath);
        $diskFree = @disk_free_space($diskPath);

        $diskUsedPercentage = 0;

        if ($diskTotal && $diskFree !== false) {
            $diskUsed = $diskTotal - $diskFree;
            $diskUsedPercentage = round(($diskUsed / $diskTotal) * 100, 2);
        }

        $loadAverage = null;

        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();

            if (is_array($load)) {
                $loadAverage = round($load[0], 2);
            }
        }

        return response()->json([
            'timestamp' => now()->toDateTimeString(),

            'memory' => [
                'current' => $this->formatBytes(memory_get_usage(true)),
                'peak' => $this->formatBytes(memory_get_peak_usage(true)),
            ],

            'disk' => [
                'used_percentage' => $diskUsedPercentage,
                'free' => $diskFree !== false
                    ? $this->formatBytes($diskFree)
                    : 'Unknown',
            ],

            'load_average' => $loadAverage,

            'php_version' => PHP_VERSION,

            'laravel_version' => app()->version(),

            'environment' => app()->environment(),
        ]);
    }

    /**
     * Convert bytes into readable format.
     */
    private function formatBytes($bytes)
    {
        if ($bytes === false || $bytes === null) {
            return 'Unknown';
        }

        $units = [
            'B',
            'KB',
            'MB',
            'GB',
            'TB',
        ];

        $bytes = max((float) $bytes, 0);
        $power = $bytes > 0
            ? floor(log($bytes, 1024))
            : 0;

        $power = min($power, count($units) - 1);

        return round(
            $bytes / pow(1024, $power),
            2
        ) . ' ' . $units[$power];
    }
}