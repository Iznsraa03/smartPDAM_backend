<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\InvoiceStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('meter_reading_id')
                    ->relationship('meterReading', 'id'),
                TextInput::make('invoice_number')
                    ->required(),
                TextInput::make('previous_reading')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('current_reading')
                    ->required()
                    ->numeric(),
                TextInput::make('usage')
                    ->required()
                    ->numeric(),
                TextInput::make('water_cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('administration_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('penalty_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric(),
                DatePicker::make('due_date')
                    ->required(),
                TextInput::make('billing_period')
                    ->required(),
                Select::make('status')
                    ->options(InvoiceStatus::class)
                    ->default('unpaid')
                    ->required(),
            ]);
    }
}
