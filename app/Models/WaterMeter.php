<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MeterType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WaterMeter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meter_number',
        'meter_type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'meter_type' => MeterType::class,
            'is_active'  => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function meterReadings(): HasMany
    {
        return $this->hasMany(MeterReading::class);
    }

    public function latestReading(): ?MeterReading
    {
        return $this->meterReadings()->latest('reading_date')->first();
    }
}
