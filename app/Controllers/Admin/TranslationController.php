<?php

namespace App\Controllers\Admin;

use App\Core\Response;
use App\Models\Language; // Assuming Language model exists

class TranslationController
{
    public function index()
    {
        // Simple stub
        return Response::success(['message' => 'Translation management']);
    }
}
