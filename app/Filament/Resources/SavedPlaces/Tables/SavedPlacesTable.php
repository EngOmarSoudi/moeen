<?php

namespace App\Filament\Resources\SavedPlaces\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SavedPlacesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('place_type')
                    ->label('Type')
                    ->badge()
                    ->colors([
                        'primary' => 'airport',
                        'success' => 'hotel',
                        'warning' => 'bus_station',
                        'info' => 'train_station',
                        'danger' => 'landmark',
                        'secondary' => fn ($state): bool => in_array($state, ['office', 'residential', 'other']),
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'airport' => 'Airport',
                        'hotel' => 'Hotel',
                        'bus_station' => 'Bus Station',
                        'train_station' => 'Train Station',
                        'landmark' => 'Landmark',
                        'office' => 'Office',
                        'residential' => 'Residential',
                        default => 'Other',
                    }),

                TextColumn::make('address')
                    ->label('Address')
                    ->limit(40)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('latitude')
                    ->label('Lat')
                    ->numeric(8)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('longitude')
                    ->label('Lng')
                    ->numeric(8)
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('place_type')
                    ->label('Type')
                    ->options([
                        'airport' => 'Airport',
                        'hotel' => 'Hotel',
                        'bus_station' => 'Bus Station',
                        'train_station' => 'Train Station',
                        'landmark' => 'Landmark',
                        'office' => 'Office',
                        'residential' => 'Residential',
                        'other' => 'Other',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }
}
