<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'province',
        'city',
        'district',
        'village',
        'full_address',
        'latitude',
        'longitude',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'latitude'   => 'float',
            'longitude'  => 'float',
            'is_primary' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
