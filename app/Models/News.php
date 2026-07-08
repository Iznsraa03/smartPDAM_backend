<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NewsStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'thumbnail',
        'author',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'status'       => NewsStatus::class,
            'published_at' => 'datetime',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', NewsStatus::Published)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->status === NewsStatus::Published;
    }
}
