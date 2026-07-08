<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $notifications = $this->notificationRepository->paginateForUser($request->user()->id);

        return response()->json(NotificationResource::collection($notifications)->response()->getData(true));
    }

    public function markAsRead(Request $request, int $id): JsonResponse
    {
        $this->notificationRepository->markAsRead($id, $request->user()->id);

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $count = $this->notificationRepository->markAllAsRead($request->user()->id);

        return response()->json(['message' => "{$count} notifications marked as read."]);
    }
}
