<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'content'       => $this->content,
            'thumbnail_url' => $this->thumbnail
                ? asset('storage/' . $this->thumbnail)
                : null,
            'author'        => $this->author,
            'status'        => $this->status->value,
            'published_at'  => $this->published_at,
            'created_at'    => $this->created_at,
        ];
    }
}
