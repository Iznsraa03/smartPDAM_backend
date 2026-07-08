<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\TariffGroup;
use App\Models\TariffRate;
use App\Repositories\Contracts\TariffRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TariffRepository implements TariffRepositoryInterface
{
    public function __construct(
        private readonly TariffGroup $groupModel,
        private readonly TariffRate $rateModel,
    ) {}

    public function findActiveGroup(): ?TariffGroup
    {
        return $this->groupModel
            ->where('is_active', true)
            ->with('tariffRates')
            ->first();
    }

    public function findGroupById(int $id): ?TariffGroup
    {
        return $this->groupModel->with('tariffRates')->find($id);
    }

    public function allGroups(): Collection
    {
        return $this->groupModel->with('tariffRates')->get();
    }

    public function createGroup(array $data): TariffGroup
    {
        return $this->groupModel->create($data);
    }

    public function updateGroup(TariffGroup $group, array $data): TariffGroup
    {
        $group->update($data);

        return $group->fresh('tariffRates');
    }

    public function deleteGroup(TariffGroup $group): bool
    {
        return $group->delete();
    }

    public function createRate(array $data): TariffRate
    {
        return $this->rateModel->create($data);
    }

    public function updateRate(TariffRate $rate, array $data): TariffRate
    {
        $rate->update($data);

        return $rate->fresh();
    }

    public function deleteRate(TariffRate $rate): bool
    {
        return $rate->delete();
    }
}
