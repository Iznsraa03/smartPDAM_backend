<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsRequest;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;

class AdminNewsController extends Controller
{
    public function __construct(private readonly NewsService $newsService) {}

    public function index(): JsonResponse
    {
        $news = $this->newsService->listAll();

        return response()->json(NewsResource::collection($news)->response()->getData(true));
    }

    public function store(StoreNewsRequest $request): JsonResponse
    {
        $news = $this->newsService->create($request->validated(), $request->file('thumbnail'));

        return response()->json([
            'message' => 'News created successfully.',
            'news'    => new NewsResource($news),
        ], 201);
    }

    public function update(StoreNewsRequest $request, News $news): JsonResponse
    {
        $updated = $this->newsService->update($news, $request->validated(), $request->file('thumbnail'));

        return response()->json([
            'message' => 'News updated successfully.',
            'news'    => new NewsResource($updated),
        ]);
    }

    public function destroy(News $news): JsonResponse
    {
        $this->newsService->delete($news);

        return response()->json(['message' => 'News deleted.']);
    }
}
