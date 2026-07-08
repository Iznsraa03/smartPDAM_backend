<?php

namespace App\Filament\Resources\WaterMeters;

use App\Filament\Resources\WaterMeters\Pages\CreateWaterMeter;
use App\Filament\Resources\WaterMeters\Pages\EditWaterMeter;
use App\Filament\Resources\WaterMeters\Pages\ListWaterMeters;
use App\Filament\Resources\WaterMeters\Pages\ViewWaterMeter;
use App\Filament\Resources\WaterMeters\Schemas\WaterMeterForm;
use App\Filament\Resources\WaterMeters\Schemas\WaterMeterInfolist;
use App\Filament\Resources\WaterMeters\Tables\WaterMetersTable;
use App\Models\WaterMeter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WaterMeterResource extends Resource
{
    protected static ?string $model = WaterMeter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return WaterMeterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WaterMeterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WaterMetersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWaterMeters::route('/'),
            'create' => CreateWaterMeter::route('/create'),
            'view' => ViewWaterMeter::route('/{record}'),
            'edit' => EditWaterMeter::route('/{record}/edit'),
        ];
    }
}
