<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Events\PaymentSettled;
use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class PaymentService
{
    public function __construct(
        private readonly PaymentRepositoryInterface $paymentRepository,
        private readonly InvoiceRepositoryInterface $invoiceRepository,
    ) {
        MidtransConfig::$serverKey    = config('services.midtrans.server_key');
        MidtransConfig::$clientKey    = config('services.midtrans.client_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production', false);
        MidtransConfig::$isSanitized  = true;
        MidtransConfig::$is3ds        = true;
    }

    public function createSnapTransaction(Invoice $invoice): Payment
    {
        if ($invoice->isPaid()) {
            throw ValidationException::withMessages([
                'invoice' => ['Invoice has already been paid.'],
            ]);
        }

        $orderId = 'PDAM-' . $invoice->id . '-' . strtoupper(Str::random(8));

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $invoice->total_amount,
            ],
            'customer_details' => [
                'first_name' => $invoice->user->name,
                'email'      => $invoice->user->email,
                'phone'      => $invoice->user->phone,
            ],
            'item_details' => [
                [
                    'id'       => 'WATER_BILL',
                    'price'    => (int) $invoice->total_amount,
                    'quantity' => 1,
                    'name'     => 'Tagihan Air - ' . $invoice->billing_period,
                ],
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return $this->paymentRepository->create([
            'invoice_id'     => $invoice->id,
            'order_id'       => $orderId,
            'gross_amount'   => $invoice->total_amount,
            'snap_token'     => $snapToken,
            'payment_status' => PaymentStatus::Pending,
        ]);
    }

    public function handleWebhook(array $payload): void
    {
        // Verify signature
        $this->verifySignature($payload);

        $payment = $this->paymentRepository->findByOrderId($payload['order_id']);

        if ($payment === null) {
            return; // Unknown order — silently ignore
        }

        // Idempotency: skip if already settled
        if ($payment->isSettled()) {
            return;
        }

        $newStatus = PaymentStatus::from($payload['transaction_status']);

        $this->paymentRepository->update($payment, [
            'transaction_id'    => $payload['transaction_id'] ?? null,
            'payment_method'    => $payload['payment_type'] ?? null,
            'payment_status'    => $newStatus,
            'midtrans_response' => $payload,
            'paid_at'           => $newStatus === PaymentStatus::Settlement ? now() : null,
        ]);

        if ($newStatus === PaymentStatus::Settlement) {
            $this->invoiceRepository->update($payment->invoice, [
                'status' => InvoiceStatus::Paid,
            ]);

            event(new PaymentSettled($payment->fresh()));
        }
    }

    private function verifySignature(array $payload): void
    {
        $serverKey     = config('services.midtrans.server_key');
        $signatureKey  = hash('sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $serverKey
        );

        if ($signatureKey !== ($payload['signature_key'] ?? '')) {
            throw ValidationException::withMessages([
                'signature' => ['Invalid webhook signature.'],
            ]);
        }
    }
}
