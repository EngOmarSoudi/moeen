<?php

namespace App\Filament\Resources\Alerts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AlertsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alertType.name')
                    ->label(__('resources.alerts.fields.alert_type'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('trip.id')
                    ->label(__('resources.alerts.fields.trip'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('driver.name')
                    ->label(__('resources.alerts.fields.driver'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('vehicle.id')
                    ->label(__('resources.alerts.fields.vehicle'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->label(__('resources.alerts.fields.title'))
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('resources.alerts.fields.status'))
                    ->searchable(),
                TextColumn::make('resolved_at')
                    ->label(__('resources.alerts.fields.resolved_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('resolved_by')
                    ->label(__('resources.alerts.fields.resolved_by'))
                    ->numeric()
                    ->sortable(),
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
