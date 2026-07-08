<?php

namespace App\Filament\Resources\News\Schemas;

use App\Enums\NewsStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('thumbnail'),
                TextInput::make('author'),
                Select::make('status')
                    ->options(NewsStatus::class)
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('published_at'),
            ]);
    }
}
