<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DatabaseBackupNotification;
use App\Models\Admin;

class CreateDatabaseBackup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct($user = null)
    {
        $this->user = $user;
    }

    public function handle()
    {
        try {
            // Get the admin user who initiated the backup (if available)
            $adminUser = $this->user;

            // Create the backup using the installed spatie package
            $exitCode = Artisan::call('backup:run', [
                '--only-db' => true
            ]);

            if ($exitCode !== 0) {
                $errorMessage = "Backup command failed with exit code: {$exitCode}";
                Log::error($errorMessage);

                // Notify the admin about the failure
                if ($adminUser) {
                    $adminUser->notify(new DatabaseBackupNotification(
                        "Database backup failed: {$errorMessage}"
                    ));
                }

                throw new \Exception($errorMessage);
            }

            // Find the latest backup file to include in the notification
            $backupPath = storage_path('app/backups/' . env('APP_NAME', 'Laravel'));
            $files = [];

            if (is_dir($backupPath)) {
                $files = array_diff(scandir($backupPath), array('.', '..'));
            }

            $latestFile = null;
            if (!empty($files)) {
                // Get the most recent file
                $latestFile = array_pop($files);
            }

            $successMessage = "Database backup created successfully";
            if ($latestFile) {
                $successMessage .= ": {$latestFile}";
            }

            Log::info($successMessage);

            // Notify the admin about the successful backup
            if ($adminUser) {
                $adminUser->notify(new DatabaseBackupNotification(
                    $successMessage,
                    $latestFile
                ));
            }

        } catch (\Exception $e) {
            Log::error("Database backup failed: " . $e->getMessage());
            throw $e;
        }
    }
}