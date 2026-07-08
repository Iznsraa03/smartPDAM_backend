<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function create(array $data): User;

    public function update(User $user, array $data): User;

    public function delete(User $user): bool;

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function updateFcmToken(User $user, string $token): void;
}
