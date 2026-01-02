<?php

use App\Core\Router;

// Admin dashboard & Analytics
Router::get('/admin/dashboard', ['App\Controllers\Admin\DashboardController', 'index']);
Router::get('/admin/analytics', ['App\Controllers\Admin\AnalyticsController', 'index']);

// User management
Router::get('/admin/users', ['App\Controllers\Admin\UserController', 'index']);
Router::post('/admin/users', ['App\Controllers\Admin\UserController', 'store']);
Router::get('/admin/users/{id}', ['App\Controllers\Admin\UserController', 'show']);
Router::put('/admin/users/{id}', ['App\Controllers\Admin\UserController', 'update']);
Router::delete('/admin/users/{id}', ['App\Controllers\Admin\UserController', 'delete']);

// Content management - Chapters
Router::get('/admin/chapters', ['App\Controllers\Admin\ContentController', 'getChapters']);
Router::post('/admin/chapters', ['App\Controllers\Admin\ContentController', 'createChapter']);
Router::put('/admin/chapters/{id}', ['App\Controllers\Admin\ContentController', 'updateChapter']);
Router::delete('/admin/chapters/{id}', ['App\Controllers\Admin\ContentController', 'deleteChapter']);

// Content management - Topics
Router::get('/admin/topics', ['App\Controllers\Admin\ContentController', 'getTopics']);
Router::post('/admin/topics', ['App\Controllers\Admin\ContentController', 'createTopic']);
Router::put('/admin/topics/{id}', ['App\Controllers\Admin\ContentController', 'updateTopic']);
Router::delete('/admin/topics/{id}', ['App\Controllers\Admin\ContentController', 'deleteTopic']);

// Content management - Questions
Router::get('/admin/questions', ['App\Controllers\Admin\ContentController', 'getQuestions']);
Router::post('/admin/questions', ['App\Controllers\Admin\ContentController', 'createQuestion']);
Router::put('/admin/questions/{id}', ['App\Controllers\Admin\ContentController', 'updateQuestion']);
Router::delete('/admin/questions/{id}', ['App\Controllers\Admin\ContentController', 'deleteQuestion']);

// Terminologies
Router::get('/admin/terminologies', ['App\Controllers\Admin\TerminologyController', 'index']);
Router::post('/admin/terminologies', ['App\Controllers\Admin\TerminologyController', 'store']);
Router::put('/admin/terminologies/{id}', ['App\Controllers\Admin\TerminologyController', 'update']);
Router::delete('/admin/terminologies/{id}', ['App\Controllers\Admin\TerminologyController', 'delete']);

// Test management
Router::get('/admin/tests', ['App\Controllers\Admin\TestController', 'index']);
Router::post('/admin/tests', ['App\Controllers\Admin\TestController', 'createTest']);
Router::put('/admin/tests/{id}', ['App\Controllers\Admin\TestController', 'updateTest']);
Router::delete('/admin/tests/{id}', ['App\Controllers\Admin\TestController', 'deleteTest']);

// Settings management
Router::get('/admin/settings', ['App\Controllers\Admin\SettingsController', 'index']);
Router::post('/admin/settings', ['App\Controllers\Admin\SettingsController', 'updateSettings']);

// Translation management (Stub if needed, or remove if not used yet)
Router::get('/admin/translations', ['App\Controllers\Admin\TranslationController', 'index']);