<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Illuminate\Http\JsonResponse;

class AdminDashboardController extends Controller
{
    public function __construct(private readonly AdminDashboardService $adminDashboardService) {}

    public function __invoke(): JsonResponse
    {
        return response()->json($this->adminDashboardService->getAdminDashboard());
    }
}
