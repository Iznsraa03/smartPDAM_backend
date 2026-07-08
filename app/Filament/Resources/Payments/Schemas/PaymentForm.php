<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Enums\PaymentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('invoice_id')
                    ->relationship('invoice', 'id')
                    ->required(),
                TextInput::make('order_id')
                    ->required(),
                TextInput::make('transaction_id'),
                TextInput::make('payment_method'),
                TextInput::make('gross_amount')
                    ->required()
                    ->numeric(),
                Select::make('payment_status')
                    ->options(PaymentStatus::class)
                    ->default('pending')
                    ->required(),
                TextInput::make('midtrans_response'),
                DateTimePicker::make('paid_at'),
            ]);
    }
}
