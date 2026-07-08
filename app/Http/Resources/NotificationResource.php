<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'message'    => $this->message,
            'type'       => $this->type->value,
            'type_label' => $this->type->label(),
            'is_read'    => $this->is_read,
            'read_at'    => $this->read_at,
            'created_at' => $this->created_at,
        ];
    }
}
