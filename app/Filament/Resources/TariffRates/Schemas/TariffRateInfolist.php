<?php

namespace App\Filament\Resources\TariffRates\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TariffRateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tariffGroup.name')
                    ->label('Tariff group'),
                TextEntry::make('start_range')
                    ->numeric(),
                TextEntry::make('end_range')
                    ->numeric(),
                TextEntry::make('price_per_m3')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
