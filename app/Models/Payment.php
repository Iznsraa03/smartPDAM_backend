<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'order_id',
        'transaction_id',
        'payment_method',
        'gross_amount',
        'snap_token',
        'payment_status',
        'midtrans_response',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount'      => 'decimal:2',
            'payment_status'    => PaymentStatus::class,
            'midtrans_response' => 'array',
            'paid_at'           => 'datetime',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function isSettled(): bool
    {
        return $this->payment_status === PaymentStatus::Settlement;
    }
}
