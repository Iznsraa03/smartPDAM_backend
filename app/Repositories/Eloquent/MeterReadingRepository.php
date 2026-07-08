<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Enums\MeterReadingStatus;
use App\Models\MeterReading;
use App\Models\WaterMeter;
use App\Repositories\Contracts\MeterReadingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MeterReadingRepository implements MeterReadingRepositoryInterface
{
    public function __construct(
        private readonly MeterReading $model,
        private readonly WaterMeter $waterMeterModel,
    ) {}

    public function findById(int $id): ?MeterReading
    {
        return $this->model->with('waterMeter.user')->find($id);
    }

    public function create(array $data): MeterReading
    {
        return $this->model->create($data);
    }

    public function update(MeterReading $reading, array $data): MeterReading
    {
        $reading->update($data);

        return $reading->fresh();
    }

    public function latestForMeter(int $waterMeterId): ?MeterReading
    {
        return $this->model
            ->where('water_meter_id', $waterMeterId)
            ->latest('reading_date')
            ->first();
    }

    public function paginateByStatus(MeterReadingStatus $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with('waterMeter.user')
            ->where('status', $status)
            ->latest()
            ->paginate($perPage);
    }

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->whereHas('waterMeter', fn ($q) => $q->where('user_id', $userId))
            ->with('waterMeter')
            ->latest('reading_date')
            ->paginate($perPage);
    }

    public function monthlyUsageForUser(int $userId, int $months = 6): Collection
    {
        return $this->model
            ->whereHas('waterMeter', fn ($q) => $q->where('user_id', $userId))
            ->where('status', MeterReadingStatus::Approved)
            ->where('reading_date', '>=', now()->subMonths($months)->startOfMonth())
            ->orderBy('reading_date')
            ->get(['reading_date', 'usage']);
    }
}
