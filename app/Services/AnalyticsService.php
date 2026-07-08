<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\MeterReadingStatus;
use App\Models\User;
use App\Repositories\Contracts\MeterReadingRepositoryInterface;

class AnalyticsService
{
    public function __construct(
        private readonly MeterReadingRepositoryInterface $meterReadingRepository,
    ) {}

    public function getUsageAnalytics(User $user): array
    {
        $readings = $this->meterReadingRepository->monthlyUsageForUser($user->id, 6);

        $current  = $readings->last();
        $previous = $readings->count() >= 2 ? $readings->get($readings->count() - 2) : null;

        $currentUsage  = $current ? (float) $current->usage : 0;
        $previousUsage = $previous ? (float) $previous->usage : 0;

        $percentageDiff = $previousUsage > 0
            ? round((($currentUsage - $previousUsage) / $previousUsage) * 100, 1)
            : 0;

        return [
            'current_month_usage'  => $currentUsage,
            'previous_month_usage' => $previousUsage,
            'percentage_difference' => $percentageDiff,
            'trend'                => $percentageDiff >= 0 ? 'up' : 'down',
            'monthly_trend'        => $readings->map(fn ($r) => [
                'month' => $r->reading_date->format('Y-m'),
                'usage' => (float) $r->usage,
            ])->values(),
        ];
    }
}
