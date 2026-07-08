<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('meterReading.id')
                    ->searchable(),
                TextColumn::make('invoice_number')
                    ->searchable(),
                TextColumn::make('previous_reading')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('current_reading')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('usage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('water_cost')
                    ->money()
                    ->sortable(),
                TextColumn::make('administration_fee')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('penalty_fee')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('billing_period')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
