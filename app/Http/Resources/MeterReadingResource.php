<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeterReadingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'water_meter_id'   => $this->water_meter_id,
            'previous_reading' => (float) $this->previous_reading,
            'current_reading'  => (float) $this->current_reading,
            'usage'            => (float) $this->usage,
            'meter_photo_url'  => $this->meter_photo
                ? asset('storage/' . $this->meter_photo)
                : null,
            'reading_date'     => $this->reading_date->toDateString(),
            'status'           => $this->status->value,
            'status_label'     => $this->status->label(),
            'rejection_reason' => $this->rejection_reason,
            'created_at'       => $this->created_at,
            'water_meter'      => $this->whenLoaded('waterMeter', fn () => [
                'id'           => $this->waterMeter->id,
                'meter_number' => $this->waterMeter->meter_number,
                'meter_type'   => $this->waterMeter->meter_type->value,
            ]),
        ];
    }
}
