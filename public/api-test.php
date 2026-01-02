<?php
header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'message' => 'API is working! The Insurance Guide backend is running successfully.',
    'timestamp' => date('Y-m-d H:i:s'),
    'environment' => $_ENV['APP_ENV'] ?? 'unknown'
]);
?>