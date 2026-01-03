<?php

namespace Tests\Feature;

use App\Models\Maintenance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceModeTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_blocks_guests_when_maintenance_active(): void
    {
        $this->get('/')->assertOk();

        Maintenance::create([
            'maintenance_mode' => true,
            'title' => 'Upgrades in progress',
            'subtitle' => 'Please check back shortly.',
            'allowed_ips' => ['10.0.0.1'],
            'starts_at' => now()->subMinute(),
            'ends_at' => now()->addHour(),
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '20.20.20.20'])
            ->get('/')
            ->assertStatus(503)
            ->assertSee('Upgrades in progress');

        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
            ->get('/')
            ->assertOk();
    }
}
