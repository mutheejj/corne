<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('elections:start-scheduled')->everyMinute();
Schedule::command('elections:end-active')->everyMinute();
Schedule::command('notifications:election-reminders')->hourly();
