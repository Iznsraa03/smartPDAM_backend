<?php

namespace App\Filament\Resources\Addresses\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AddressInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('province'),
                TextEntry::make('city'),
                TextEntry::make('district'),
                TextEntry::make('village'),
                TextEntry::make('full_address')
                    ->columnSpanFull(),
                TextEntry::make('latitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('longitude')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('is_primary')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
