<?php

declare(strict_types=1);

namespace App\Enums;

enum InvoiceStatus: string
{
    case Unpaid  = 'unpaid';
    case Paid    = 'paid';
    case Overdue = 'overdue';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid  => 'Unpaid',
            self::Paid    => 'Paid',
            self::Overdue => 'Overdue',
        };
    }

    public function isPending(): bool
    {
        return in_array($this, [self::Unpaid, self::Overdue]);
    }
}
