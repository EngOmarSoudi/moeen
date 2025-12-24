<?php

namespace App\Filament\Resources\Trips\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TripsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Trip Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'scheduled',
                        'primary' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'scheduled',
                        'heroicon-o-arrow-path' => 'in_progress',
                        'heroicon-o-check-circle' => 'completed',
                        'heroicon-o-x-circle' => 'cancelled',
                    ]),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('driver.name')
                    ->label('Driver')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Not Assigned'),
                TextColumn::make('vehicle.plate_number')
                    ->label('Vehicle')
                    ->sortable()
                    ->placeholder('Not Assigned'),
                TextColumn::make('tripType.name')
                    ->label('Type')
                    ->sortable()
                    ->badge(),
                TextColumn::make('origin')
                    ->label('Origin')
                    ->searchable()
                    ->limit(20),
                TextColumn::make('destination')
                    ->label('Destination')
                    ->searchable()
                    ->limit(20),
                TextColumn::make('start_at')
                    ->label('Start Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                TextColumn::make('passenger_count')
                    ->label('Passengers')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('final_amount')
                    ->label('Amount')
                    ->money('SAR')
                    ->sortable()
                    ->alignEnd(),
                BadgeColumn::make('service_kind')
                    ->label('Service')
                    ->colors([
                        'primary' => 'trip',
                        'warning' => 'hotel_booking',
                        'success' => 'package',
                    ])
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('agent.name')
                    ->label('Agent')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple(),
                SelectFilter::make('service_kind')
                    ->label('Service Type')
                    ->options([
                        'trip' => 'Trip',
                        'hotel_booking' => 'Hotel Booking',
                        'package' => 'Package',
                    ]),
                SelectFilter::make('trip_type_id')
                    ->label('Trip Type')
                    ->relationship('tripType', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('driver_id')
                    ->label('Driver')
                    ->relationship('driver', 'name')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_at', 'desc');
    }
}
