<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class MeterReadingDTO
{
    public function __construct(
        public readonly int $waterMeterId,
        public readonly float $currentReading,
        public readonly string $readingDate,
        public readonly ?string $meterPhotoPath,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            waterMeterId:    (int) $data['water_meter_id'],
            currentReading:  (float) $data['current_reading'],
            readingDate:     $data['reading_date'] ?? now()->toDateString(),
            meterPhotoPath:  $data['meter_photo_path'] ?? null,
        );
    }
}
