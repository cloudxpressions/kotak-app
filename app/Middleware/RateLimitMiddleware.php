<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class RateLimitMiddleware
{
    private static $maxRequests = 60; // Per minute
    private static $timeWindow = 60; // In seconds

    public static function handle()
    {
        $ip = self::getClientIP();
        $key = 'rate_limit_' . $ip;
        
        // In a real implementation, you'd use Redis or a database to track requests
        // For this example, we'll simulate with a file-based approach
        $cacheFile = dirname(__DIR__, 3) . '/storage/cache/' . $key . '.txt';
        
        if (file_exists($cacheFile)) {
            $data = json_decode(file_get_contents($cacheFile), true);
            $requests = $data['requests'] ?? 0;
            $timestamp = $data['timestamp'] ?? 0;
            
            // Check if the time window has passed
            if (time() - $timestamp < self::$timeWindow) {
                if ($requests >= self::$maxRequests) {
                    Response::error('Rate limit exceeded', 429, [
                        'retry_after' => self::$timeWindow - (time() - $timestamp)
                    ]);
                } else {
                    // Increment request count
                    self::updateRequestCount($cacheFile, $requests + 1, $timestamp);
                }
            } else {
                // Reset counter for new time window
                self::updateRequestCount($cacheFile, 1, time());
            }
        } else {
            // First request from this IP
            self::updateRequestCount($cacheFile, 1, time());
        }

        return true;
    }

    private static function updateRequestCount($cacheFile, $requests, $timestamp)
    {
        $data = [
            'requests' => $requests,
            'timestamp' => $timestamp
        ];
        file_put_contents($cacheFile, json_encode($data));
    }

    private static function getClientIP()
    {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}