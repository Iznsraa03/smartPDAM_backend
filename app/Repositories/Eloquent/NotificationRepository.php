<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\PdamNotification;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function __construct(private readonly PdamNotification $model) {}

    public function create(array $data): PdamNotification
    {
        return $this->model->create($data);
    }

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        return (bool) $this->model
            ->where('id', $notificationId)
            ->where('user_id', $userId)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function markAllAsRead(int $userId): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function unreadCount(int $userId): int
    {
        return $this->model->where('user_id', $userId)->where('is_read', false)->count();
    }
}
