<?php

declare(strict_types=1);

namespace App\Enums;

enum MeterReadingStatus: string
{
    case Pending       = 'pending';
    case Approved      = 'approved';
    case Rejected      = 'rejected';
    case PendingReview = 'pending_review';

    public function label(): string
    {
        return match ($this) {
            self::Pending       => 'Pending',
            self::Approved      => 'Approved',
            self::Rejected      => 'Rejected',
            self::PendingReview => 'Pending Review',
        };
    }

    public function canGenerateInvoice(): bool
    {
        return $this === self::Approved;
    }
}
