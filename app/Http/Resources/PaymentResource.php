<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'order_id'       => $this->order_id,
            'transaction_id' => $this->transaction_id,
            'payment_method' => $this->payment_method,
            'gross_amount'   => (float) $this->gross_amount,
            'snap_token'     => $this->snap_token,
            'payment_status' => $this->payment_status->value,
            'status_label'   => $this->payment_status->label(),
            'paid_at'        => $this->paid_at,
            'created_at'     => $this->created_at,
        ];
    }
}
