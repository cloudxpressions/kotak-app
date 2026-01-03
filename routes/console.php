<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('backup:clean')->daily()->at('00:00'); // Clean old backups at midnight
Schedule::command('backup:run --only-db')->daily()->at('01:30'); // Run database backup at 1:30 AM
Schedule::command('backup:monitor')->daily()->at('02:00'); // Monitor backup health at 2:00 AM
