<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\NotificationType;
use App\Events\InvoiceGenerated;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBillingReminderNotification implements ShouldQueue
{
    public function __construct(private readonly NotificationService $notificationService) {}

    public function handle(InvoiceGenerated $event): void
    {
        $invoice = $event->invoice;

        $this->notificationService->send(
            user:    $invoice->user,
            title:   'Tagihan Air Baru',
            message: "Tagihan air bulan {$invoice->billing_period} Anda sebesar Rp " .
                     number_format((float) $invoice->total_amount, 0, ',', '.') .
                     " sudah tersedia. Jatuh tempo: {$invoice->due_date->format('d M Y')}.",
            type:    NotificationType::Billing,
        );
    }
}
