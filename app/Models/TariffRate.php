<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TariffRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'tariff_group_id',
        'start_range',
        'end_range',
        'price_per_m3',
    ];

    protected function casts(): array
    {
        return [
            'start_range'  => 'integer',
            'end_range'    => 'integer',
            'price_per_m3' => 'decimal:2',
        ];
    }

    public function tariffGroup(): BelongsTo
    {
        return $this->belongsTo(TariffGroup::class);
    }

    /**
     * Determine if this rate applies to a given usage block.
     */
    public function appliesTo(float $usage): bool
    {
        $withinStart = $usage >= $this->start_range;
        $withinEnd   = $this->end_range === 0 || $usage <= $this->end_range;

        return $withinStart && $withinEnd;
    }
}
