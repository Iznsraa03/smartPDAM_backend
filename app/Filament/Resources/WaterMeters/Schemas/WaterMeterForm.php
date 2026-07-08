<?php

namespace App\Filament\Resources\WaterMeters\Schemas;

use App\Enums\MeterType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WaterMeterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('meter_number')
                    ->required(),
                Select::make('meter_type')
                    ->options(MeterType::class)
                    ->default('residential')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
