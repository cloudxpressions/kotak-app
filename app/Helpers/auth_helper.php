<?php

if (!function_exists('auth')) {
    function auth()
    {
        return \App\Core\Auth::class;
    }
}

if (!function_exists('current_user')) {
    function current_user()
    {
        return \App\Core\Auth::user();
    }
}

if (!function_exists('auth_id')) {
    function auth_id()
    {
        return \App\Core\Auth::id();
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in()
    {
        return \App\Core\Auth::check();
    }
}