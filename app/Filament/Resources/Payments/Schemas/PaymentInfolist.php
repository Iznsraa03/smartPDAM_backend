<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('invoice.id')
                    ->label('Invoice'),
                TextEntry::make('order_id'),
                TextEntry::make('transaction_id')
                    ->placeholder('-'),
                TextEntry::make('payment_method')
                    ->placeholder('-'),
                TextEntry::make('gross_amount')
                    ->numeric(),
                TextEntry::make('payment_status')
                    ->badge(),
                TextEntry::make('paid_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
