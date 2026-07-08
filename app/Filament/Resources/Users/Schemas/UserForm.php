<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('address')
                    ->label('Alamat Lengkap (Ketik Manual)'),
                TextInput::make('latitude')
                    ->label('Latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->label('Longitude')
                    ->numeric(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('role')
                    ->options(UserRole::class)
                    ->default('customer')
                    ->required(),
                Select::make('status')
                    ->options(UserStatus::class)
                    ->default('active')
                    ->required(),
            ]);
    }
}
