<?php

use App\Jobs\ProcessSubscriptionDowngrades;
use App\Jobs\FinishIdleSessionsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::job(new FinishIdleSessionsJob)
    ->everyMinute()
    ->name('finish_idle_sessions');

//TODO: Switch this to everyHour() when we're ready to go live
Schedule::job(new ProcessSubscriptionDowngrades)
    ->everyMinute()
    ->name('process_subscription_downgrades');

//TODO: Switch this to everyHour() when we're ready to go live
Schedule::job(new \App\Jobs\ExpireRecords())
    ->everyMinute()
    ->name('expire_records');
