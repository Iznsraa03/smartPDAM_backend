<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\DTOs\MeterReadingDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\MeterReading\StoreMeterReadingRequest;
use App\Http\Resources\MeterReadingResource;
use App\Models\MeterReading;
use App\Repositories\Contracts\MeterReadingRepositoryInterface;
use App\Services\MeterReadingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeterReadingController extends Controller
{
    public function __construct(
        private readonly MeterReadingService $meterReadingService,
        private readonly MeterReadingRepositoryInterface $meterReadingRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $readings = $this->meterReadingRepository->paginateForUser($request->user()->id);

        return response()->json(MeterReadingResource::collection($readings)->response()->getData(true));
    }

    public function store(StoreMeterReadingRequest $request): JsonResponse
    {
        $dto = MeterReadingDTO::fromArray([
            'water_meter_id'  => $request->water_meter_id,
            'current_reading' => $request->reading_value,
            'reading_date'    => $request->reading_date ?? now()->toDateString(),
        ]);

        $reading = $this->meterReadingService->submit(
            $request->user(),
            $dto,
            $request->file('photo_path'),
        );

        return response()->json([
            'message' => 'Meter reading submitted successfully.',
            'reading' => new MeterReadingResource($reading->load('waterMeter')),
        ], 201);
    }

    public function show(Request $request, MeterReading $meterReading): JsonResponse
    {
        $this->authorize('view', $meterReading);

        return response()->json(new MeterReadingResource($meterReading->load('waterMeter')));
    }

    public function simulate(Request $request, \App\Services\WaterTariffCalculationService $tariffService): JsonResponse
    {
        $request->validate([
            'water_meter_id' => 'required|integer|exists:water_meters,id',
            'reading_value'  => 'required|numeric',
        ]);

        $waterMeter = \App\Models\WaterMeter::findOrFail($request->water_meter_id);
        $latestReading = $waterMeter->meterReadings()->latest('reading_date')->first();
        $previousReading = $latestReading ? (float) $latestReading->current_reading : 0.0;

        $currentReading = (float) $request->reading_value;

        if ($currentReading <= $previousReading) {
            return response()->json([
                'message' => 'Current reading must be greater than previous reading',
                'previous_reading' => $previousReading
            ], 422);
        }

        $calculation = $tariffService->calculate($previousReading, $currentReading);

        return response()->json($calculation->toArray());
    }
}
