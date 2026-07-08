<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\News;
use App\Repositories\Contracts\NewsRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NewsRepository implements NewsRepositoryInterface
{
    public function __construct(private readonly News $model) {}

    public function findById(int $id): ?News
    {
        return $this->model->find($id);
    }

    public function create(array $data): News
    {
        return $this->model->create($data);
    }

    public function update(News $news, array $data): News
    {
        $news->update($data);

        return $news->fresh();
    }

    public function delete(News $news): bool
    {
        return $news->delete();
    }

    public function paginatePublished(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->published()->latest('published_at')->paginate($perPage);
    }

    public function paginateAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($perPage);
    }
}
