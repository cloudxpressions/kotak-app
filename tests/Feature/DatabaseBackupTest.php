<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;
use App\Models\Admin;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class DatabaseBackupTest extends TestCase
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

        // Ensure the backup directories exist
        $backupPath = storage_path('app/backups/Laravel');
        if (!File::isDirectory($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $publicBackupPath = storage_path('app/public/database-backup');
        if (!File::isDirectory($publicBackupPath)) {
            File::makeDirectory($publicBackupPath, 0755, true);
        }
    }

    public function test_backup_workflow()
    {
        // Test 1: Admin can access the backup page
        $response = $this->actingAs($this->admin, 'admin')
                         ->get('/admin/system/database-backups');
        
        $response->assertStatus(200)
                 ->assertViewIs('admin.system.database-backups.index');
        
        // Test 2: The backup page loads with AJAX request for DataTables
        $response = $this->actingAs($this->admin, 'admin')
                         ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                         ->get('/admin/system/database-backups');

        // This should return JSON data for DataTables (empty initially if no files)
        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/json');
        
        // Test 3: Admin can trigger a new backup creation
        Queue::fake(); // Fake the queue to prevent actual job processing during tests

        // Get the page first to establish session and get CSRF token
        $response = $this->actingAs($this->admin, 'admin')
                         ->get('/admin/system/database-backups');

        // Extract the CSRF token from the session
        $token = csrf_token();

        // Create backup request with proper CSRF token
        $response = $this->actingAs($this->admin, 'admin')
                         ->post('/admin/system/database-backups/create', [
                             '_token' => $token,
                         ]);

        // Should redirect back with success message
        $response->assertStatus(302); // Redirect after POST
        
        // Test 4: Create a dummy backup file to test the workflow
        $backupPath = storage_path('app/backups/' . env('APP_NAME', 'Laravel'));
        $dummyBackup = $backupPath . '/test_backup_' . time() . '.zip';

        // Create a dummy ZIP file
        $zip = new \ZipArchive();
        if ($zip->open($dummyBackup, \ZipArchive::CREATE)) {
            $zip->addFromString('test.sql', '/* Test backup content */');
            $zip->close();
        }

        $this->assertTrue(File::exists($dummyBackup), 'Dummy backup file should be created');

        // Test 5: Verify that backup files appear in the AJAX response
        $response = $this->actingAs($this->admin, 'admin')
                         ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                         ->get('/admin/system/database-backups');

        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data['data'], 'Response should contain data array');
        $this->assertNotEmpty($data['data'], 'Data array should not be empty after backup creation');

        // Check that the response contains expected fields
        $firstRecord = $data['data'][0];
        $this->assertArrayHasKey('name', $firstRecord, 'Record should contain name');
        $this->assertArrayHasKey('size', $firstRecord, 'Record should contain size');
        $this->assertArrayHasKey('created_at', $firstRecord, 'Record should contain created_at');

        // Verify that the file has a .zip extension
        $this->assertStringEndsWith('.zip', $firstRecord['name'], 'Backup file should have .zip extension');

        // Test 6: Test backup download feature
        $backupFile = $firstRecord['name'];
        $response = $this->actingAs($this->admin, 'admin')
                         ->get("/admin/system/database-backups/download/{$backupFile}");

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/zip');

        // Test 7: Test backup deletion
        // First, make sure the file exists before deletion
        $deletePath = $backupPath . '/' . $backupFile;
        $this->assertTrue(File::exists($deletePath), 'Backup file should exist before deletion');

        // Perform delete request (for API endpoints, we might need to pass the token differently)
        $response = $this->actingAs($this->admin, 'admin')
                         ->withHeaders([
                             'X-CSRF-TOKEN' => csrf_token(),
                             'X-Requested-With' => 'XMLHttpRequest'
                         ])
                         ->delete("/admin/system/database-backups/{$backupFile}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        // Verify the file was actually deleted
        $this->assertFalse(File::exists($deletePath), 'Backup file should be deleted after API call');

        // Verify that the deleted file no longer appears in the AJAX response
        $response = $this->actingAs($this->admin, 'admin')
                         ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                         ->get('/admin/system/database-backups');

        $data = json_decode($response->getContent(), true);
        $deletedFileExists = false;
        foreach ($data['data'] as $record) {
            if ($record['name'] === $backupFile) {
                $deletedFileExists = true;
                break;
            }
        }

        $this->assertFalse($deletedFileExists, 'Deleted backup file should not appear in the list anymore');
        
        echo "All database backup workflow tests passed successfully!\n";
    }
}