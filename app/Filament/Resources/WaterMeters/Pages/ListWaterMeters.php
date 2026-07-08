<?php

namespace App\Filament\Resources\WaterMeters\Pages;

use App\Filament\Resources\WaterMeters\WaterMeterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWaterMeters extends ListRecords
{
    protected static string $resource = WaterMeterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
