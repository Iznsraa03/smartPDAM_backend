<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeterReadingResource;
use App\Models\MeterReading;
use App\Repositories\Contracts\MeterReadingRepositoryInterface;
use App\Services\MeterReadingService;
use App\Enums\MeterReadingStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminMeterReadingController extends Controller
{
    public function __construct(
        private readonly MeterReadingRepositoryInterface $meterReadingRepository,
        private readonly MeterReadingService $meterReadingService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $status   = $request->get('status', 'pending');
        $readings = $this->meterReadingRepository
            ->paginateByStatus(MeterReadingStatus::from($status));

        return response()->json(MeterReadingResource::collection($readings)->response()->getData(true));
    }

    public function approve(MeterReading $meterReading): JsonResponse
    {
        $reading = $this->meterReadingService->approve($meterReading);

        return response()->json([
            'message' => 'Meter reading approved. Invoice generated.',
            'reading' => new MeterReadingResource($reading),
        ]);
    }

    public function reject(Request $request, MeterReading $meterReading): JsonResponse
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $reading = $this->meterReadingService->reject($meterReading, $request->reason);

        return response()->json([
            'message' => 'Meter reading rejected.',
            'reading' => new MeterReadingResource($reading),
        ]);
    }
}
