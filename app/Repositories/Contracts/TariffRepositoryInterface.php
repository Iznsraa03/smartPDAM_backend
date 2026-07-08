<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\TariffGroup;
use Illuminate\Database\Eloquent\Collection;

interface TariffRepositoryInterface
{
    public function findActiveGroup(): ?TariffGroup;

    public function findGroupById(int $id): ?TariffGroup;

    public function allGroups(): Collection;

    public function createGroup(array $data): TariffGroup;

    public function updateGroup(TariffGroup $group, array $data): TariffGroup;

    public function deleteGroup(TariffGroup $group): bool;

    public function createRate(array $data): \App\Models\TariffRate;

    public function updateRate(\App\Models\TariffRate $rate, array $data): \App\Models\TariffRate;

    public function deleteRate(\App\Models\TariffRate $rate): bool;
}
