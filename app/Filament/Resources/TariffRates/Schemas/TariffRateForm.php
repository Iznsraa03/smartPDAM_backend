<?php

namespace App\Filament\Resources\TariffRates\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TariffRateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tariff_group_id')
                    ->relationship('tariffGroup', 'name')
                    ->required(),
                TextInput::make('start_range')
                    ->required()
                    ->numeric(),
                TextInput::make('end_range')
                    ->required()
                    ->numeric(),
                TextInput::make('price_per_m3')
                    ->required()
                    ->numeric(),
            ]);
    }
}
