<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NewsRepositoryInterface
{
    public function findById(int $id): ?News;

    public function create(array $data): News;

    public function update(News $news, array $data): News;

    public function delete(News $news): bool;

    public function paginatePublished(int $perPage = 10): LengthAwarePaginator;

    public function paginateAll(int $perPage = 15): LengthAwarePaginator;
}
