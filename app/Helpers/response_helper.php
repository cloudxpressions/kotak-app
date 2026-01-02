<?php

if (!function_exists('success')) {
    function success($data = null, $message = 'Success', $statusCode = 200)
    {
        return \App\Core\Response::success($data, $message, $statusCode);
    }
}

if (!function_exists('error')) {
    function error($message = 'Error', $statusCode = 400, $errors = null)
    {
        return \App\Core\Response::error($message, $statusCode, $errors);
    }
}

if (!function_exists('json_response')) {
    function json_response($data, $statusCode = 200)
    {
        return \App\Core\Response::json($data, $statusCode);
    }
}