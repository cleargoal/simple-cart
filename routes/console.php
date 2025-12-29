<?php

use App\Jobs\DailySalesReport;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily sales report to run every day at 8 AM
Schedule::job(new DailySalesReport())->dailyAt('08:00');
