<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class HealthAdvancedFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_log_inspector_dashboard_renders_and_parses_logs(): void
    {
        logger()->error('Test Health Exception Log Message 123');

        $response = $this->get('/health-logs');
        $response->assertStatus(200);
        $response->assertSee('Log Inspector');
        $response->assertSee('Test Health Exception Log Message 123');
    }

    public function test_log_inspector_clear_logs(): void
    {
        logger()->error('Temporary Log Message');

        $response = $this->post('/health-logs/clear');
        $response->assertRedirect('/health-logs');

        $logContent = File::get(storage_path('logs/laravel.log'));
        $this->assertEmpty($logContent);
    }

    public function test_chaos_simulator_dashboard_renders(): void
    {
        $response = $this->get('/health-chaos');
        $response->assertStatus(200);
        $response->assertSee('Chaos Simulator');
    }

    public function test_chaos_simulator_trigger_and_reset(): void
    {
        // Trigger DB Surge Chaos
        $response1 = $this->post('/health-chaos/trigger', [
            'action' => 'db_surge',
        ]);
        $response1->assertRedirect('/health-chaos');
        $this->assertTrue(Cache::get('health_chaos_db_surge'));

        // Reset All Chaos
        $response2 = $this->post('/health-chaos/reset');
        $response2->assertRedirect('/health-chaos');
        $this->assertFalse(Cache::get('health_chaos_db_surge', false));
    }

    public function test_resource_gauges_dashboard_renders(): void
    {
        $response = $this->get('/health-gauges');
        $response->assertStatus(200);
        $response->assertSee('Resource Gauges');
    }

    public function test_resource_gauges_json_api(): void
    {
        $response = $this->get('/health-gauges-json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'timestamp',
            'memory' => ['percentage', 'used_mb', 'status'],
            'database' => ['latency_ms', 'status'],
            'disk' => ['percentage', 'status'],
            'cpu' => ['load_percentage', 'status'],
        ]);
    }
}
