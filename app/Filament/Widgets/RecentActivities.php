<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use App\Models\AuditLog;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentActivities extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => AuditLog::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('user.name')->label('User'),
                TextColumn::make('action')->label('Action'),
                TextColumn::make('created_at')->dateTime()->label('Time'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
