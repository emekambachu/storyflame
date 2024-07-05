<?php

use App\Jobs\FinishIdleSessionsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::job(new FinishIdleSessionsJob)->everyMinute()->name('finish_idle_sessions');
