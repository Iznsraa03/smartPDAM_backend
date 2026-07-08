<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MeterReadingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'water_meter_id',
        'previous_reading',
        'current_reading',
        'meter_photo',
        'reading_date',
        'status',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'previous_reading' => 'decimal:2',
            'current_reading'  => 'decimal:2',
            'usage'            => 'decimal:2',
            'reading_date'     => 'date',
            'status'           => MeterReadingStatus::class,
        ];
    }

    public function waterMeter(): BelongsTo
    {
        return $this->belongsTo(WaterMeter::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function isPending(): bool
    {
        return in_array($this->status, [MeterReadingStatus::Pending, MeterReadingStatus::PendingReview]);
    }

    public function isApproved(): bool
    {
        return $this->status === MeterReadingStatus::Approved;
    }
}
