<?php

namespace Tests\Feature;

use App\Models\BlockedIp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlockedIpMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_blocked_ip_receives_403_response(): void
    {
        BlockedIp::create([
            'ip_address' => '123.123.123.123',
            'reason' => 'Too many failed attempts',
            'blocked_until' => now()->addHour(),
            'is_permanent' => false,
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '123.123.123.123'])
            ->get('/')
            ->assertStatus(403)
            ->assertSee('Access Blocked');

        $this->assertDatabaseHas('blocked_ips', [
            'ip_address' => '123.123.123.123',
            'attempts_count' => 1,
        ]);
    }
}
