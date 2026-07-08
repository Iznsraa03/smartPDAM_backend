<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\PdamNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function create(array $data): PdamNotification;

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function markAsRead(int $notificationId, int $userId): bool;

    public function markAllAsRead(int $userId): int;

    public function unreadCount(int $userId): int;
}
