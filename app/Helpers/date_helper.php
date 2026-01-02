<?php

if (!function_exists('format_date')) {
    function format_date($date, $format = 'Y-m-d H:i:s')
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        
        return $date->format($format);
    }
}

if (!function_exists('time_ago')) {
    function time_ago($datetime)
    {
        $time = is_string($datetime) ? strtotime($datetime) : $datetime;
        $time = is_numeric($time) ? $time : strtotime($datetime);
        
        $diff = time() - $time;
        $intervals = [
            'year' => 31556952,
            'month' => 2629746,
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1
        ];

        foreach ($intervals as $interval => $seconds) {
            $ago = floor($diff / $seconds);
            if ($ago > 0) {
                return $ago . ' ' . $interval . 'ago';
            }
        }

        return 'Just now';
    }
}