<?php

namespace App\Filament\Resources\Addresses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('province')
                    ->required(),
                TextInput::make('city')
                    ->required(),
                TextInput::make('district')
                    ->required(),
                TextInput::make('village')
                    ->required(),
                Textarea::make('full_address')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                Toggle::make('is_primary')
                    ->required(),
            ]);
    }
}
