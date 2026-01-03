<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\CreateDatabaseBackup;
use Carbon\Carbon;

class DatabaseBackupController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:backup.view', only: ['index']),
            new Middleware('permission:backup.create', only: ['create']),
            new Middleware('permission:backup.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of database backups
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Look for backups in the spatie backups directory
            $backupPath = storage_path('app/backups/' . env('APP_NAME', 'Laravel'));

            if (!File::isDirectory($backupPath)) {
                // Create directory if it doesn't exist
                File::makeDirectory($backupPath, 0755, true);
            }

            $items = [];
            if (File::exists($backupPath)) {
                $files = File::allFiles($backupPath);

                foreach ($files as $file) {
                    if (pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'zip') {
                        $items[] = [
                            'name' => $file->getFilename(),
                            'size' => $this->formatBytes($file->getSize()),
                            'created_at' => Carbon::createFromTimestamp($file->getCTime())->format('Y-m-d H:i:s'),
                        ];
                    }
                }
            }

            // Sort by creation date, newest first
            $items = collect($items)->sortByDesc(function ($item) {
                return Carbon::parse($item['created_at']);
            })->values();

            return DataTables::of($items)->make(true);
        }

        // For non-AJAX requests, just return the view
        return view('admin.system.database-backups.index');
    }

    /**
     * Format file size in human readable format
     */
    private function formatBytes($size, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }

    /**
     * Create a new database backup
     */
    public function create()
    {
        try {
            CreateDatabaseBackup::dispatch(auth('admin')->user());
            return redirect()->route('admin.system.database-backups.index')
                ->with('success', 'Database backup started successfully. Refresh to view new backup file.');
        } catch (\Exception $e) {
            return redirect()->route('admin.system.database-backups.index')
                ->with('error', 'Failed to start backup: ' . $e->getMessage());
        }
    }

    /**
     * Download a specific backup file
     */
    public function download($filename)
    {
        $path = storage_path('app/backups/' . env('APP_NAME', 'Laravel') . '/' . basename($filename));

        if (!File::exists($path)) {
            abort(404, 'Backup file not found.');
        }

        return response()->download($path);
    }

    /**
     * Delete a specific backup file
     */
    public function destroy($filename)
    {
        $path = storage_path('app/backups/' . env('APP_NAME', 'Laravel') . '/' . basename($filename));

        if (!File::exists($path)) {
            return response()->json(['success' => false, 'message' => 'File not found.'], 404);
        }

        try {
            File::delete($path);

            return response()->json(['success' => true, 'message' => 'Backup deleted successfully.']);
        } catch (\Exception $e) {
            Log::error("Delete failed: {$filename} - " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}