<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Language;

class LanguageMiddleware
{
    public static function handle()
    {
        // The language detection is handled in the Language class constructor
        // This middleware ensures the language is properly set for the request
        $language = new Language();
        
        // We could set the language in a global context here if needed
        // For now, we just ensure the language is properly detected
        return true;
    }
}