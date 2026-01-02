<?php
// Router for PHP built-in server

// Parse the request URI
$requestUri = $_SERVER['REQUEST_URI'];

// Remove query string
$path = parse_url($requestUri, PHP_URL_PATH);

// If the path is for the API, route it to api.php
if (strpos($path, '/api/') === 0) {
    // Include the api.php file to handle the API request
    $_SERVER['REQUEST_URI'] = $requestUri; // Preserve the original URI
    require_once __DIR__ . '/public/api.php';
    exit;
} elseif ($path === '/' || $path === '/index.php') {
    // Redirect to admin panel
    header('Location: /admin.php');
    exit;
} elseif ($path === '/admin.php' || $path === '/admin') {
    // Serve the admin panel
    require_once __DIR__ . '/public/admin.php';
    exit;
} elseif ($path === '/api') {
    // Handle root API path
    header('Content-Type: application/json');
    echo json_encode([
        'message' => 'Insurance Guide API is running',
        'version' => '1.0.0',
        'endpoints' => [
            'auth' => '/api/auth/[register|login|logout]',
            'content' => '/api/[chapters|topics|terminologies]',
            'tests' => '/api/tests',
            'profile' => '/api/profile'
        ]
    ]);
    exit;
} else {
    // For any other path, return 404 or serve static files if they exist
    $requestedFile = __DIR__ . '/public' . $path;

    if (file_exists($requestedFile) && is_file($requestedFile)) {
        $ext = pathinfo($requestedFile, PATHINFO_EXTENSION);
        
        if ($ext === 'php') {
            require_once $requestedFile;
            exit;
        }
        
        // Serve the static file manually
        $mimes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'font/eot',
            'html' => 'text/html',
        ];
        
        $contentType = isset($mimes[$ext]) ? $mimes[$ext] : 'text/plain';
        header('Content-Type: ' . $contentType);
        readfile($requestedFile);
        exit;
    } else {
        // Handle Admin API routes (e.g. /admin/users, /admin/analytics)
        // If the path starts with /admin/ and it wasn't a file (handled above), it's an API call
        
        if (strpos($path, '/api/') === 0 || strpos($path, '/admin/') === 0) {
             $_SERVER['REQUEST_URI'] = $requestUri; 
             require_once __DIR__ . '/public/api.php';
             exit;
        }

        // Return 404 for API routes that don't match
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Endpoint not found']);
        exit;
    }
}