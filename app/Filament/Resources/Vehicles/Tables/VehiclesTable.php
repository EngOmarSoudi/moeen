<?php

namespace App\Filament\Resources\Vehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plate_number')
                    ->label(__('resources.vehicles.fields.plate_number'))
                    ->searchable(),
                TextColumn::make('vehicleType.name')
                    ->label(__('resources.vehicles.fields.type'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('model')
                    ->label(__('resources.vehicles.fields.model'))
                    ->searchable(),
                TextColumn::make('color')
                    ->label(__('resources.vehicles.fields.color'))
                    ->searchable(),
                TextColumn::make('vin')
                    ->label(__('resources.vehicles.fields.vin'))
                    ->searchable(),
                TextColumn::make('year')
                    ->label(__('resources.vehicles.fields.year'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('resources.vehicles.fields.status'))
                    ->formatStateUsing(fn ($state) => __('resources.vehicles.enums.' . $state))
                    ->searchable(),
                TextColumn::make('insurance_expiry')
                    ->label(__('resources.vehicles.fields.insurance_expiry'))
                    ->date()
                    ->sortable(),
                TextColumn::make('registration_expiry')
                    ->label(__('resources.vehicles.fields.registration_expiry'))
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('Deleted At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
