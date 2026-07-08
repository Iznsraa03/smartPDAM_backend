<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\NotificationType;
use App\Events\PaymentSettled;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPaymentNotification implements ShouldQueue
{
    public function __construct(private readonly NotificationService $notificationService) {}

    public function handle(PaymentSettled $event): void
    {
        $payment = $event->payment;
        $user    = $payment->invoice->user;

        $this->notificationService->send(
            user:    $user,
            title:   'Pembayaran Berhasil',
            message: "Tagihan {$payment->invoice->invoice_number} sebesar Rp " .
                     number_format($payment->gross_amount, 0, ',', '.') . ' telah berhasil dibayar.',
            type:    NotificationType::Payment,
        );
    }
}
