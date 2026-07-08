<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationType: string
{
    case Billing = 'billing';
    case Payment = 'payment';
    case Usage   = 'usage';
    case News    = 'news';
    case General = 'general';

    public function label(): string
    {
        return match ($this) {
            self::Billing => 'Billing',
            self::Payment => 'Payment',
            self::Usage   => 'Usage Alert',
            self::News    => 'News',
            self::General => 'General',
        };
    }
}
