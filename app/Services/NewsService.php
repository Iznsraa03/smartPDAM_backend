<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\News;
use App\Repositories\Contracts\NewsRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class NewsService
{
    public function __construct(
        private readonly NewsRepositoryInterface $newsRepository,
    ) {}

    public function create(array $data, ?UploadedFile $thumbnail = null): News
    {
        if ($thumbnail) {
            $data['thumbnail'] = Storage::disk('public')->put('news-thumbnails', $thumbnail);
        }

        return $this->newsRepository->create($data);
    }

    public function update(News $news, array $data, ?UploadedFile $thumbnail = null): News
    {
        if ($thumbnail) {
            // Delete old thumbnail if exists
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $data['thumbnail'] = Storage::disk('public')->put('news-thumbnails', $thumbnail);
        }

        return $this->newsRepository->update($news, $data);
    }

    public function delete(News $news): bool
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }

        return $this->newsRepository->delete($news);
    }

    public function listPublished(int $perPage = 10): LengthAwarePaginator
    {
        return $this->newsRepository->paginatePublished($perPage);
    }

    public function listAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->newsRepository->paginateAll($perPage);
    }
}
