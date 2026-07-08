<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\MeterReadingStatus;
use App\Events\MeterReadingSubmitted;
use App\Services\InvoiceService;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateInvoiceOnApproval implements ShouldQueue
{
    public function __construct(private readonly InvoiceService $invoiceService) {}

    public function handle(MeterReadingSubmitted $event): void
    {
        $reading = $event->meterReading;

        if ($reading->status === MeterReadingStatus::Approved) {
            $this->invoiceService->generateFromReading($reading);
        }
    }
}
