<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case PdamAdmin  = 'pdam_admin';
    case Customer   = 'customer';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::PdamAdmin  => 'PDAM Admin',
            self::Customer   => 'Customer',
        };
    }

    public function isAdmin(): bool
    {
        return in_array($this, [self::SuperAdmin, self::PdamAdmin]);
    }
}
