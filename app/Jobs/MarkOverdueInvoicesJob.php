<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\InvoiceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Scheduled job: marks all unpaid past-due invoices as overdue.
 * Runs daily via artisan scheduler.
 */
class MarkOverdueInvoicesJob implements ShouldQueue
{
    use Queueable;

    public function handle(InvoiceService $invoiceService): void
    {
        $count = $invoiceService->markOverdueInvoices();

        Log::info("MarkOverdueInvoicesJob: {$count} invoices marked as overdue.");
    }
}
