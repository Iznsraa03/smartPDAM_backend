<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'invoice_number'     => $this->invoice_number,
            'billing_period'     => $this->billing_period,
            'previous_reading'   => (float) $this->previous_reading,
            'current_reading'    => (float) $this->current_reading,
            'usage'              => (float) $this->usage,
            'water_cost'         => (float) $this->water_cost,
            'administration_fee' => (float) $this->administration_fee,
            'penalty_fee'        => (float) $this->penalty_fee,
            'total_amount'       => (float) $this->total_amount,
            'due_date'           => $this->due_date->toDateString(),
            'status'             => $this->status->value,
            'status_label'       => $this->status->label(),
            'payment'            => $this->whenLoaded('payment', fn () => new PaymentResource($this->payment)),
            'created_at'         => $this->created_at,
        ];
    }
}
