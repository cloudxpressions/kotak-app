<?php

namespace App\Controllers\Admin;

use App\Core\Response;
use App\Models\User;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\Test;

class DashboardController
{
    public function index()
    {
        // This is primarily an API backend, so we assume index might return stats
        // But for the specific route /admin/dashboard mapped to index, 
        // we might just return success or redirect if it was a view-based app.
        // Since we refactored to a single page app in public/admin.php that calls APIs,
        // this might not be hit directly as a view.
        // However, the AnalyticsController handles usage stats.
        
        return Response::success(['message' => 'Dashboard API Ready']);
    }
}
