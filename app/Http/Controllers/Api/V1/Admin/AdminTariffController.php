<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\TariffGroup;
use App\Models\TariffRate;
use App\Repositories\Contracts\TariffRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminTariffController extends Controller
{
    public function __construct(private readonly TariffRepositoryInterface $tariffRepository) {}

    public function indexGroups(): JsonResponse
    {
        return response()->json($this->tariffRepository->allGroups());
    }

    public function storeGroup(Request $request): JsonResponse
    {
        $data  = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);
        $group = $this->tariffRepository->createGroup($data);

        return response()->json(['message' => 'Tariff group created.', 'group' => $group], 201);
    }

    public function updateGroup(Request $request, TariffGroup $tariffGroup): JsonResponse
    {
        $data    = $request->validate(['name' => 'sometimes|string', 'description' => 'nullable|string', 'is_active' => 'boolean']);
        $updated = $this->tariffRepository->updateGroup($tariffGroup, $data);

        return response()->json(['message' => 'Tariff group updated.', 'group' => $updated]);
    }

    public function destroyGroup(TariffGroup $tariffGroup): JsonResponse
    {
        $this->tariffRepository->deleteGroup($tariffGroup);

        return response()->json(['message' => 'Tariff group deleted.']);
    }

    public function storeRate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tariff_group_id' => 'required|exists:tariff_groups,id',
            'start_range'     => 'required|integer|min:0',
            'end_range'       => 'required|integer|min:0',
            'price_per_m3'    => 'required|numeric|min:0',
        ]);
        $rate = $this->tariffRepository->createRate($data);

        return response()->json(['message' => 'Tariff rate created.', 'rate' => $rate], 201);
    }

    public function updateRate(Request $request, TariffRate $tariffRate): JsonResponse
    {
        $data    = $request->validate(['start_range' => 'integer', 'end_range' => 'integer', 'price_per_m3' => 'numeric']);
        $updated = $this->tariffRepository->updateRate($tariffRate, $data);

        return response()->json(['message' => 'Tariff rate updated.', 'rate' => $updated]);
    }

    public function destroyRate(TariffRate $tariffRate): JsonResponse
    {
        $this->tariffRepository->deleteRate($tariffRate);

        return response()->json(['message' => 'Tariff rate deleted.']);
    }
}
