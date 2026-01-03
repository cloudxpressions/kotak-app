<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\NewsletterSubscriber;
use App\Models\Newsletter;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();

        // Create a test admin user and assign permissions
        $this->admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Assign all newsletter permissions to admin
        $this->admin->syncPermissions([
            'newsletter.view',
            'newsletter.create',
            'newsletter.update',
            'newsletter.delete',
        ]);

        // Ensure backup directories exist
        $backupPath = storage_path('app/backups/Laravel');
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $publicBackupPath = storage_path('app/public/database-backup');
        if (!is_dir($publicBackupPath)) {
            mkdir($publicBackupPath, 0755, true);
        }
    }

    public function test_newsletter_workflow()
    {
        // Test 1: Admin can access the newsletter page
        $response = $this->actingAs($this->admin, 'admin')
                         ->get('/admin/system/newsletters');

        $response->assertStatus(200)
                 ->assertViewIs('admin.system.newsletters.index');

        // Test 2: Newsletter page loads with AJAX request for DataTables
        $response = $this->actingAs($this->admin, 'admin')
                         ->get('/admin/system/newsletters')
                         ->withHeaders(['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/json');

        // Test 3: Admin can access the create newsletter page
        $response = $this->actingAs($this->admin, 'admin')
                         ->get('/admin/system/newsletters/create');

        $response->assertStatus(200)
                 ->assertViewIs('admin.system.newsletters.create');

        // Test 4: Admin can create a new newsletter
        $response = $this->actingAs($this->admin, 'admin')
                         ->post('/admin/system/newsletters', [
                             '_token' => csrf_token(),
                             'subject' => 'Test Newsletter',
                             'body_html' => '<p>This is a test newsletter</p>',
                             'body_text' => 'This is a test newsletter',
                             'status' => 'draft',
                         ]);

        $response->assertStatus(302); // Should redirect after creation

        // Verify the newsletter was created
        $this->assertDatabaseHas('newsletters', [
            'subject' => 'Test Newsletter',
            'status' => 'draft',
        ]);

        $newsletter = Newsletter::where('subject', 'Test Newsletter')->first();

        // Test 5: Admin can access the edit page
        $response = $this->actingAs($this->admin, 'admin')
                         ->get("/admin/system/newsletters/{$newsletter->id}/edit");

        $response->assertStatus(200)
                 ->assertViewIs('admin.system.newsletters.edit');

        // Test 6: Admin can update a newsletter
        $response = $this->actingAs($this->admin, 'admin')
                         ->put("/admin/system/newsletters/{$newsletter->id}", [
                             '_token' => csrf_token(),
                             '_method' => 'PUT',
                             'subject' => 'Updated Newsletter',
                             'body_html' => '<p>This is an updated newsletter</p>',
                             'body_text' => 'This is an updated newsletter',
                             'status' => 'scheduled',
                         ]);

        $response->assertStatus(302); // Should redirect after update

        // Verify the newsletter was updated
        $this->assertDatabaseHas('newsletters', [
            'subject' => 'Updated Newsletter',
            'status' => 'scheduled',
        ]);

        // Test 7: Admin can delete a newsletter
        $response = $this->actingAs($this->admin, 'admin')
                         ->delete("/admin/system/newsletters/{$newsletter->id}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        // Verify the newsletter was deleted
        $this->assertDatabaseMissing('newsletters', [
            'id' => $newsletter->id,
        ]);

        // Test 8: Admin can access newsletter subscriber page
        $response = $this->actingAs($this->admin, 'admin')
                         ->get('/admin/system/newsletter-subscribers');

        $response->assertStatus(200)
                 ->assertViewIs('admin.system.newsletter-subscribers.index');

        // Test 9: Newsletter subscriber page loads with AJAX request
        $response = $this->actingAs($this->admin, 'admin')
                         ->get('/admin/system/newsletter-subscribers')
                         ->withHeaders(['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/json');

        // Test 10: Admin can add a subscriber
        $response = $this->actingAs($this->admin, 'admin')
                         ->post('/admin/system/newsletter-subscribers', [
                             '_token' => csrf_token(),
                             'email' => 'test@example.com',
                             'name' => 'Test User',
                             'status' => 'subscribed',
                         ]);

        $response->assertStatus(302); // Should redirect after creation

        // Verify the subscriber was created
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'test@example.com',
            'status' => 'subscribed',
        ]);

        $subscriber = NewsletterSubscriber::where('email', 'test@example.com')->first();

        // Test 11: Admin can update subscriber
        $response = $this->actingAs($this->admin, 'admin')
                         ->put("/admin/system/newsletter-subscribers/{$subscriber->id}", [
                             '_token' => csrf_token(),
                             '_method' => 'PUT',
                             'email' => 'updated@example.com',
                             'name' => 'Updated User',
                             'status' => 'unsubscribed',
                         ]);

        $response->assertStatus(200) // Should return JSON
                 ->assertJson(['success' => true]);

        // Verify the subscriber was updated
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'updated@example.com',
            'status' => 'unsubscribed',
        ]);

        // Test 12: Admin can delete subscriber
        $response = $this->actingAs($this->admin, 'admin')
                         ->delete("/admin/system/newsletter-subscribers/{$subscriber->id}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        // Verify the subscriber was deleted
        $this->assertDatabaseMissing('newsletter_subscribers', [
            'id' => $subscriber->id,
        ]);

        echo "All newsletter functionality tests passed successfully!\n";
    }
}