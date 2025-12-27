<?php

namespace App\Filament\Resources\TripAssignments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TripAssignmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trip.code')
                    ->label('Trip Code')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('trip.origin')
                    ->label('Origin')
                    ->sortable()
                    ->limit(30),

                TextColumn::make('trip.destination')
                    ->label('Destination')
                    ->sortable()
                    ->limit(30),

                TextColumn::make('driver.name')
                    ->label('Assigned Driver')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('driver.phone')
                    ->label('Driver Phone')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'pending',
                        'info' => 'confirmed',
                        'warning' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'declined',
                        'secondary' => 'canceled',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->sortable(),

                TextColumn::make('sequence_number')
                    ->label('Seq #')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('trip.start_at')
                    ->label('Trip Start')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('assigned_at')
                    ->label('Assigned At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('confirmed_at')
                    ->label('Confirmed At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('started_at')
                    ->label('Started At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('trip.start_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Assignment Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'declined' => 'Declined',
                        'canceled' => 'Canceled',
                    ])
                    ->multiple(),

                Filter::make('trip_in_future')
                    ->label('Upcoming Trips')
                    ->query(fn ($query) => $query->whereHas('trip', fn ($q) => $q->where('start_at', '>', now()))),

                Filter::make('trip_in_past')
                    ->label('Past Trips')
                    ->query(fn ($query) => $query->whereHas('trip', fn ($q) => $q->where('start_at', '<', now()))),
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
