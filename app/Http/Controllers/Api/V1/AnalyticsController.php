<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct(private readonly AnalyticsService $analyticsService) {}

    public function usage(Request $request): JsonResponse
    {
        $data = $this->analyticsService->getUsageAnalytics($request->user());

        return response()->json($data);
    }
}
