<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'province'     => $this->province,
            'city'         => $this->city,
            'district'     => $this->district,
            'village'      => $this->village,
            'full_address' => $this->full_address,
            'latitude'     => $this->latitude,
            'longitude'    => $this->longitude,
            'is_primary'   => $this->is_primary,
            'created_at'   => $this->created_at,
        ];
    }
}
