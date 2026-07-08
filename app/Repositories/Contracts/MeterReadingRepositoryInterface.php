<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\MeterReading;
use App\Enums\MeterReadingStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface MeterReadingRepositoryInterface
{
    public function findById(int $id): ?MeterReading;

    public function create(array $data): MeterReading;

    public function update(MeterReading $reading, array $data): MeterReading;

    public function latestForMeter(int $waterMeterId): ?MeterReading;

    public function paginateByStatus(MeterReadingStatus $status, int $perPage = 15): LengthAwarePaginator;

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function monthlyUsageForUser(int $userId, int $months = 6): Collection;
}
