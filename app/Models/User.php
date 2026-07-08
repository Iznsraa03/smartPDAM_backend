<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'latitude',
        'longitude',
        'password',
        'role',
        'status',
        'fcm_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
            'status'            => UserStatus::class,
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function waterMeters(): HasMany
    {
        return $this->hasMany(WaterMeter::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function pdamNotifications(): HasMany
    {
        return $this->hasMany(PdamNotification::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }

    public function primaryAddress(): ?Address
    {
        return $this->addresses()->where('is_primary', true)->first();
    }

    public function latestInvoice(): ?Invoice
    {
        return $this->invoices()->latest()->first();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() && $this->isActive();
    }
}
