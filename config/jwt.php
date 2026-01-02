<?php

return [
    'secret' => $_ENV['JWT_SECRET'] ?? 'your_jwt_secret_key_here',
    'algorithm' => 'HS256',
    'expires_in' => 60 * 24, // 24 hours
    'refresh_expires_in' => 60 * 24 * 30, // 30 days
];