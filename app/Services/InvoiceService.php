<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\TariffCalculationDTO;
use App\Enums\InvoiceStatus;
use App\Events\InvoiceGenerated;
use App\Models\Invoice;
use App\Models\MeterReading;
use App\Models\User;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Support\Str;

class InvoiceService
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly WaterTariffCalculationService $tariffService,
    ) {}

    public function generateFromReading(MeterReading $reading): Invoice
    {
        $user          = $reading->waterMeter->user;
        $billingPeriod = $reading->reading_date->format('Y-m');

        // Idempotency: don't generate duplicate invoices for the same period
        if ($this->invoiceRepository->existsForPeriod($user->id, $billingPeriod)) {
            return $this->invoiceRepository
                ->paginateForUser($user->id, 1)
                ->first();
        }

        $isLate  = now()->day > 20; // After 20th = late
        $calculation = $this->tariffService->calculate(
            (float) $reading->previous_reading,
            (float) $reading->current_reading,
            $isLate,
        );

        $invoice = $this->invoiceRepository->create([
            'user_id'            => $user->id,
            'meter_reading_id'   => $reading->id,
            'invoice_number'     => $this->generateInvoiceNumber(),
            'previous_reading'   => $calculation->previousReading,
            'current_reading'    => $calculation->currentReading,
            'usage'              => $calculation->usage,
            'water_cost'         => $calculation->waterCost,
            'administration_fee' => $calculation->administrationFee,
            'maintenance_fee'    => $calculation->maintenanceFee,
            'penalty_fee'        => $calculation->penaltyFee,
            'total_amount'       => $calculation->totalAmount,
            'due_date'           => now()->addDays(14),
            'billing_period'     => $billingPeriod,
            'status'             => InvoiceStatus::Unpaid,
        ]);

        event(new InvoiceGenerated($invoice));

        return $invoice;
    }

    public function markOverdueInvoices(): int
    {
        $overdue = $this->invoiceRepository->getOverdueInvoices();
        $count   = 0;

        foreach ($overdue as $invoice) {
            $this->invoiceRepository->update($invoice, ['status' => InvoiceStatus::Overdue]);
            $count++;
        }

        return $count;
    }

    private function generateInvoiceNumber(): string
    {
        return 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }
}
