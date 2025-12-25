<?php

namespace App\Filament\Resources\TravelRoutes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TravelRoutesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('resources.travel_routes.fields.name_en'))
                    ->searchable(),
                TextColumn::make('name_ar')
                    ->label(__('resources.travel_routes.fields.name_ar'))
                    ->searchable(),
                TextColumn::make('origin')
                    ->label(__('resources.trips.fields.origin'))
                    ->searchable(),
                TextColumn::make('destination')
                    ->label(__('resources.trips.fields.destination'))
                    ->searchable(),
                TextColumn::make('distance_km')
                    ->label(__('resources.travel_routes.fields.distance'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('duration_minutes')
                    ->label(__('resources.travel_routes.fields.duration'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('route_type')
                    ->label(__('resources.travel_routes.fields.type'))
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label(__('resources.travel_routes.fields.active'))
                    ->boolean(),
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
