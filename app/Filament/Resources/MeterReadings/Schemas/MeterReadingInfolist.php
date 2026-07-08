<?php

namespace App\Filament\Resources\MeterReadings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MeterReadingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('waterMeter.id')
                    ->label('Water meter'),
                TextEntry::make('previous_reading')
                    ->numeric(),
                TextEntry::make('current_reading')
                    ->numeric(),
                TextEntry::make('usage')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('meter_photo')
                    ->placeholder('-'),
                TextEntry::make('reading_date')
                    ->date(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('rejection_reason')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
