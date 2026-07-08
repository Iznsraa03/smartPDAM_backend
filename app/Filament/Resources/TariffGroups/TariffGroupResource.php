<?php

namespace App\Filament\Resources\TariffGroups;

use App\Filament\Resources\TariffGroups\Pages\CreateTariffGroup;
use App\Filament\Resources\TariffGroups\Pages\EditTariffGroup;
use App\Filament\Resources\TariffGroups\Pages\ListTariffGroups;
use App\Filament\Resources\TariffGroups\Pages\ViewTariffGroup;
use App\Filament\Resources\TariffGroups\Schemas\TariffGroupForm;
use App\Filament\Resources\TariffGroups\Schemas\TariffGroupInfolist;
use App\Filament\Resources\TariffGroups\Tables\TariffGroupsTable;
use App\Models\TariffGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TariffGroupResource extends Resource
{
    protected static ?string $model = TariffGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TariffGroupForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TariffGroupInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TariffGroupsTable::configure($table);
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
            'index' => ListTariffGroups::route('/'),
            'create' => CreateTariffGroup::route('/create'),
            'view' => ViewTariffGroup::route('/{record}'),
            'edit' => EditTariffGroup::route('/{record}/edit'),
        ];
    }
}
