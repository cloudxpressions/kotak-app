<?php

namespace App\Controllers\Admin;

use App\Core\Response;
use App\Models\User;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\Test;

class AnalyticsController
{
    public function index()
    {
        // Aggregate counts
        // Note: Models might need 'count()' method implemented or we use raw query
        // Assuming basic count is available or we fetch all and count (inefficient but works for small app)
        // Better: User::count() if implemented in Core\Model
        
        $stats = [
            'users' => User::count(),
            'chapters' => Chapter::count(),
            'questions' => Question::count(),
            'tests' => Test::count(),
        ];
        
        return Response::success($stats);
    }
}
