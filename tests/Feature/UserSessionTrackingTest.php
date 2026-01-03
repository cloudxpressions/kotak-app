<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserSessionTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_session_is_recorded_and_toggled_on_logout(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $session = UserSession::first();
        $this->assertNotNull($session);
        $this->assertTrue($session->is_active);
        $this->assertNull($session->logout_at);
        $this->assertEquals($user->id, $session->authenticatable_id);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();

        $session->refresh();
        $this->assertNotNull($session->last_seen_at);

        $this->post('/logout')->assertRedirect('/');

        $session->refresh();
        $this->assertFalse($session->is_active);
        $this->assertNotNull($session->logout_at);
    }
}
