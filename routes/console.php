<?php

use App\Jobs\MarkOverdueInvoicesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ─── Scheduler ────────────────────────────────────────────────────────────────

// Mark overdue invoices daily at midnight
Schedule::job(new MarkOverdueInvoicesJob())->dailyAt('00:30')->name('mark-overdue-invoices');
