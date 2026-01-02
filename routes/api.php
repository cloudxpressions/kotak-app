<?php

use App\Core\Router;

// Auth routes
Router::post('/api/auth/register', ['App\Controllers\Api\AuthController', 'register']);
Router::post('/api/auth/login', ['App\Controllers\Api\AuthController', 'login']);
Router::post('/api/auth/logout', ['App\Controllers\Api\AuthController', 'logout']);
Router::post('/api/auth/refresh', ['App\Controllers\Api\AuthController', 'refresh']);

// Profile routes
Router::get('/api/profile', ['App\Controllers\Api\ProfileController', 'show']);
Router::put('/api/profile', ['App\Controllers\Api\ProfileController', 'update']);

// Content routes
Router::get('/api/chapters', ['App\Controllers\Api\ContentController', 'getChapters']);
Router::get('/api/chapters/{id}', ['App\Controllers\Api\ContentController', 'getChapter']);
Router::get('/api/topics', ['App\Controllers\Api\ContentController', 'getTopics']);
Router::get('/api/topics/{id}', ['App\Controllers\Api\ContentController', 'getTopic']);
Router::get('/api/terminologies', ['App\Controllers\Api\ContentController', 'getTerminologies']);
Router::get('/api/terminologies/{id}', ['App\Controllers\Api\ContentController', 'getTerminology']);

// Test routes
Router::get('/api/tests', ['App\Controllers\Api\TestController', 'getTests']);
Router::post('/api/tests/{id}/attempt', ['App\Controllers\Api\TestController', 'startTest']);
Router::post('/api/tests/{id}/submit', ['App\Controllers\Api\TestController', 'submitTest']);

// Performance routes
Router::get('/api/performance', ['App\Controllers\Api\PerformanceController', 'getPerformance']);

// Bookmark routes
Router::post('/api/save', ['App\Controllers\Api\BookmarkController', 'save']);
Router::delete('/api/save', ['App\Controllers\Api\BookmarkController', 'remove']);
Router::get('/api/saved-items', ['App\Controllers\Api\BookmarkController', 'getSavedItems']);

// Settings routes
Router::get('/api/settings', ['App\Controllers\Api\SettingsController', 'getSettings']);

// Ads routes
Router::post('/api/ads/track', ['App\Controllers\Api\AdsController', 'track']);