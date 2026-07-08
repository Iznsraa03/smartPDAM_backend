<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\MeterReadingStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use App\Repositories\Contracts\MeterReadingRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class AdminDashboardService
{
    public function __construct(
        private readonly UserRepositoryInterface          $userRepository,
        private readonly InvoiceRepositoryInterface       $invoiceRepository,
        private readonly MeterReadingRepositoryInterface  $meterReadingRepository,
    ) {}

    public function getAdminDashboard(): array
    {
        $monthlyRevenue = $this->invoiceRepository->monthlyRevenue(12);
        $totalRevenue   = collect($monthlyRevenue)->sum('revenue');

        return [
            'total_customers'        => User::where('role', UserRole::Customer)->count(),
            'active_customers'       => User::where('role', UserRole::Customer)->where('status', UserStatus::Active)->count(),
            'monthly_revenue'        => $monthlyRevenue,
            'total_revenue'          => $totalRevenue,
            'pending_meter_readings' => $this->countPendingReadings(),
            'paid_invoices'          => $this->invoiceRepository->countByStatus(InvoiceStatus::Paid),
            'unpaid_invoices'        => $this->invoiceRepository->countByStatus(InvoiceStatus::Unpaid),
            'overdue_invoices'       => $this->invoiceRepository->countByStatus(InvoiceStatus::Overdue),
        ];
    }

    private function countPendingReadings(): int
    {
        return $this->meterReadingRepository
            ->paginateByStatus(MeterReadingStatus::Pending, 1)
            ->total()
            + $this->meterReadingRepository
            ->paginateByStatus(MeterReadingStatus::PendingReview, 1)
            ->total();
    }
}
