<?php

namespace App\Controllers\Admin;

use App\Core\Response;
use App\Core\Request;
use App\Models\Setting;

class SettingsController
{
    public function index()
    {
        // Settings typically key-value pairs
        $settings = Setting::all();
        // optionally format as key=>value object
        return Response::success($settings);
    }
    
    public function updateSettings()
    {
        $data = Request::all();
        // Assuming data is array of settings to update
        // foreach($data as $key => $value) ... 
        // For now simple stub
        return Response::success($data, 'Settings updated');
    }
}
