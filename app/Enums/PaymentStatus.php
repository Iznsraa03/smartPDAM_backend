<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending    = 'pending';
    case Settlement = 'settlement';
    case Expire     = 'expire';
    case Cancel     = 'cancel';
    case Deny       = 'deny';

    public function label(): string
    {
        return match ($this) {
            self::Pending    => 'Pending',
            self::Settlement => 'Settlement',
            self::Expire     => 'Expired',
            self::Cancel     => 'Cancelled',
            self::Deny       => 'Denied',
        };
    }

    public function isSuccessful(): bool
    {
        return $this === self::Settlement;
    }

    public function isFailed(): bool
    {
        return in_array($this, [self::Expire, self::Cancel, self::Deny]);
    }
}
