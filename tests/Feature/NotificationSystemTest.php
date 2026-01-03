<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;
use App\Models\Admin;
use App\Notifications\DatabaseBackupNotification;

class NotificationSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        
        // Run the role permission seeder to create permissions
        $this->seed('RolePermissionSeeder');
        
        // Create a test admin user
        $this->admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        
        // Assign all backup permissions to the admin user
        $this->admin->syncPermissions([
            'backup.view',
            'backup.create', 
            'backup.delete',
            'backup.download'
        ]);
    }

    public function test_notification_system_works_with_backup()
    {
        // Initially, the admin should have no notifications
        $this->assertCount(0, $this->admin->notifications);

        // Create a test notification
        $this->admin->notify(new DatabaseBackupNotification(
            'Test database backup completed successfully',
            'test_backup_20230101.zip'
        ));

        // Refresh the admin to get the latest notifications
        $this->admin->refresh();

        // Check that the notification was stored
        $this->assertCount(1, $this->admin->notifications);

        // Verify the notification content
        $notification = $this->admin->notifications->first();
        $this->assertEquals('Test database backup completed successfully', $notification->data['message']);
        $this->assertEquals('test_backup_20230101.zip', $notification->data['backup_file']);
        $this->assertEquals('database_backup', $notification->data['type']);
        $this->assertNull($notification->read_at); // Should be unread initially
        
        // Test marking notification as read
        $response = $this->actingAs($this->admin, 'admin')
                         ->withHeaders([
                             'X-CSRF-TOKEN' => csrf_token(),
                             'X-Requested-With' => 'XMLHttpRequest'
                         ])
                         ->put("/admin/notifications/{$notification->id}/read");

        $response->assertStatus(200);

        // Refresh the notification to get the latest state
        $notification->refresh();
        $this->assertNotNull($notification->read_at); // Should now be read

        // Test getting unread notifications
        $response = $this->actingAs($this->admin, 'admin')
                         ->get('/admin/notifications/unread');

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertEquals(0, $responseData['count']); // Should be 0 since we marked it as read

        // Test getting all notifications
        $response = $this->actingAs($this->admin, 'admin')
                         ->get('/admin/notifications');

        $response->assertStatus(200);
        $this->assertIsArray($response->json()['data']); // Should return paginated data

        // Test deleting the notification
        $response = $this->actingAs($this->admin, 'admin')
                         ->withHeaders([
                             'X-CSRF-TOKEN' => csrf_token(),
                             'X-Requested-With' => 'XMLHttpRequest'
                         ])
                         ->delete("/admin/notifications/{$notification->id}");

        $response->assertStatus(200);

        // Verify the notification was deleted
        $this->admin->refresh(); // Refresh to get latest state
        $this->assertCount(0, $this->admin->notifications);
        
        echo "Notification system tests passed successfully!\n";
    }
    
    public function test_notification_dropdown_api()
    {
        // Create multiple notifications
        $this->admin->notify(new DatabaseBackupNotification('First notification'));
        $this->admin->notify(new DatabaseBackupNotification('Second notification'));
        
        // Test unread notifications API
        $response = $this->actingAs($this->admin, 'admin')
                         ->get('/admin/notifications/unread');

        $response->assertStatus(200);
        $responseData = $response->json();

        $this->assertEquals(2, $responseData['count']);
        $this->assertIsArray($responseData['notifications']);

        // Check that both notifications are present (order might vary)
        $messages = array_column($responseData['notifications'], 'data.message');
        $this->assertContains('First notification', $messages);
        $this->assertContains('Second notification', $messages);
        
        // Mark all as read
        $response = $this->actingAs($this->admin, 'admin')
                         ->withHeaders([
                             'X-CSRF-TOKEN' => csrf_token(),
                             'X-Requested-With' => 'XMLHttpRequest'
                         ])
                         ->put('/admin/notifications/read-all');

        $response->assertStatus(200);

        // Verify all are now read
        $response = $this->actingAs($this->admin, 'admin')
                         ->get('/admin/notifications/unread');

        $response->assertStatus(200);
        $this->assertEquals(0, $response->json()['count']);

        echo "Notification dropdown API tests passed successfully!\n";
    }
}