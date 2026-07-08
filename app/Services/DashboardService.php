<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\MeterReadingStatus;
use App\Models\User;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use App\Repositories\Contracts\MeterReadingRepositoryInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\Contracts\NewsRepositoryInterface;

class DashboardService
{
    public function __construct(
        private readonly InvoiceRepositoryInterface      $invoiceRepository,
        private readonly MeterReadingRepositoryInterface $meterReadingRepository,
        private readonly NotificationRepositoryInterface $notificationRepository,
        private readonly NewsRepositoryInterface          $newsRepository,
    ) {}

    public function getCustomerDashboard(User $user): array
    {
        $latestInvoice = $user->invoices()->with('payment')->latest()->first();
        $latestReading = $this->meterReadingRepository->paginateForUser($user->id, 1)->first();

        return [
            'profile'          => $user,
            'current_bill'     => $latestInvoice,
            'due_date'         => $latestInvoice?->due_date,
            'payment_status'   => $latestInvoice?->status,
            'latest_reading'   => $latestReading,
            'monthly_usage'    => $this->meterReadingRepository->monthlyUsageForUser($user->id, 6),
            'unread_notifications' => $this->notificationRepository->unreadCount($user->id),
            'recent_notifications' => $this->notificationRepository->paginateForUser($user->id, 5)->items(),
            'latest_news'      => $this->newsRepository->paginatePublished(3)->items(),
        ];
    }
}
