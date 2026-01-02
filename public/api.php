<?php

// API Entry Point

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');

// Set charset
header('Charset: utf-8');

// Load Composer autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// Load helper functions
require_once dirname(__DIR__) . '/app/Helpers/response_helper.php';
require_once dirname(__DIR__) . '/app/Helpers/auth_helper.php';
require_once dirname(__DIR__) . '/app/Helpers/language_helper.php';
require_once dirname(__DIR__) . '/app/Helpers/date_helper.php';

// For PHP built-in server, handle routing manually
if (php_sapi_name() === 'cli-server') {
    // This is needed for the PHP built-in server
    $file = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (strpos($file, '.') !== false && $file !== '/') {
        // Serve static files directly if they exist
        return false;
    }
}

// Initialize the application
try {
    $app = \App\Core\App::getInstance();
    $app->run();
} catch (Exception $e) {
    // Log the error
    error_log("Application Error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error',
        'error' => $e->getMessage()
    ]);
}