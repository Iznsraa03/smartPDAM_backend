<?php

declare(strict_types=1);

namespace App\Enums;

enum MeterType: string
{
    case Residential = 'residential';
    case Commercial  = 'commercial';
    case Industrial  = 'industrial';

    public function label(): string
    {
        return match ($this) {
            self::Residential => 'Residential',
            self::Commercial  => 'Commercial',
            self::Industrial  => 'Industrial',
        };
    }
}
