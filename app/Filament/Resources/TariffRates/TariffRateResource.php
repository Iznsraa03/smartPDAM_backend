<?php

namespace App\Filament\Resources\TariffRates;

use App\Filament\Resources\TariffRates\Pages\CreateTariffRate;
use App\Filament\Resources\TariffRates\Pages\EditTariffRate;
use App\Filament\Resources\TariffRates\Pages\ListTariffRates;
use App\Filament\Resources\TariffRates\Pages\ViewTariffRate;
use App\Filament\Resources\TariffRates\Schemas\TariffRateForm;
use App\Filament\Resources\TariffRates\Schemas\TariffRateInfolist;
use App\Filament\Resources\TariffRates\Tables\TariffRatesTable;
use App\Models\TariffRate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TariffRateResource extends Resource
{
    protected static ?string $model = TariffRate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TariffRateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TariffRateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TariffRatesTable::configure($table);
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
            'index' => ListTariffRates::route('/'),
            'create' => CreateTariffRate::route('/create'),
            'view' => ViewTariffRate::route('/{record}'),
            'edit' => EditTariffRate::route('/{record}/edit'),
        ];
    }
}
