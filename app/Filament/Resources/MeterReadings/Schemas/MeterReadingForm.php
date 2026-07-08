<?php

namespace App\Filament\Resources\MeterReadings\Schemas;

use App\Enums\MeterReadingStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MeterReadingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('water_meter_id')
                    ->relationship('waterMeter', 'id')
                    ->required(),
                TextInput::make('previous_reading')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('current_reading')
                    ->required()
                    ->numeric(),
                TextInput::make('usage')
                    ->numeric(),
                TextInput::make('meter_photo'),
                DatePicker::make('reading_date')
                    ->required(),
                Select::make('status')
                    ->options(MeterReadingStatus::class)
                    ->default('pending')
                    ->required(),
                Textarea::make('rejection_reason')
                    ->columnSpanFull(),
            ]);
    }
}
