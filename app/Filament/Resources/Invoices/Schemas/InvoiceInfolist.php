<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('meterReading.id')
                    ->label('Meter reading')
                    ->placeholder('-'),
                TextEntry::make('invoice_number'),
                TextEntry::make('previous_reading')
                    ->numeric(),
                TextEntry::make('current_reading')
                    ->numeric(),
                TextEntry::make('usage')
                    ->numeric(),
                TextEntry::make('water_cost')
                    ->money(),
                TextEntry::make('administration_fee')
                    ->numeric(),
                TextEntry::make('penalty_fee')
                    ->numeric(),
                TextEntry::make('total_amount')
                    ->numeric(),
                TextEntry::make('due_date')
                    ->date(),
                TextEntry::make('billing_period'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
