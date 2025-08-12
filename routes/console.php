<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('employees:recharge-balances')
    ->dailyAt('00:00')
    ->timezone(config('app.schedule_timezone', config('app.timezone', 'UTC')));
