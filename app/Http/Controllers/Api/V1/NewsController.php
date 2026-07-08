<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(private readonly NewsService $newsService) {}

    public function index(Request $request): JsonResponse
    {
        $news = $this->newsService->listPublished((int) $request->get('per_page', 10));

        return response()->json(NewsResource::collection($news)->response()->getData(true));
    }

    public function show(News $news): JsonResponse
    {
        abort_unless($news->isPublished(), 404);

        return response()->json(new NewsResource($news));
    }
}
