<?php

use App\Console\Commands\ExpireSubscriptions;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Scheduled Tasks ───────────────────────────────────────────────────────────

// Expire subscriptions and sync user premium flags once every day at midnight.
// Make sure your server cron is running:  * * * * * php artisan schedule:run
Schedule::command(ExpireSubscriptions::class)->daily()->onOneServer();