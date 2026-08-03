<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:work --stop-when-empty --max-jobs=50')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->onOneServer();

// Clean up expired password reset tokens hourly
Schedule::command('auth:clear-resets')
    ->hourly()
    ->withoutOverlapping()
    ->onOneServer();

// Clean up expired API tokens daily to keep the database light
Schedule::command('sanctum:prune-expired --hours=24')
    ->daily()
    ->withoutOverlapping()
    ->onOneServer();

