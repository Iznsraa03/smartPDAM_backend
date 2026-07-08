<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meter_reading_id',
        'invoice_number',
        'previous_reading',
        'current_reading',
        'usage',
        'water_cost',
        'administration_fee',
        'penalty_fee',
        'total_amount',
        'due_date',
        'billing_period',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'previous_reading'   => 'decimal:2',
            'current_reading'    => 'decimal:2',
            'usage'              => 'decimal:2',
            'water_cost'         => 'decimal:2',
            'administration_fee' => 'decimal:2',
            'penalty_fee'        => 'decimal:2',
            'total_amount'       => 'decimal:2',
            'due_date'           => 'date',
            'status'             => InvoiceStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function meterReading(): BelongsTo
    {
        return $this->belongsTo(MeterReading::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function isPaid(): bool
    {
        return $this->status === InvoiceStatus::Paid;
    }

    public function isOverdue(): bool
    {
        return $this->status === InvoiceStatus::Overdue;
    }
}
