<?php

namespace App\Filament\Widgets;

use App\Models\TripAssignment;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class DriverAssignmentsWidget extends BaseWidget
{
    protected static ?string $heading = 'My Assigned Trips';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        $driverId = auth()->user()?->driver?->id;

        return $table
            ->query(
                TripAssignment::query()
                    ->when($driverId, fn (Builder $query) => $query->where('driver_id', $driverId))
                    ->active()
                    ->with('trip', 'driver')
                    ->latest('assigned_at')
            )
            ->columns([
                TextColumn::make('trip.code')
                    ->label('Trip Code')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('trip.origin')
                    ->label('From')
                    ->sortable()
                    ->limit(25),

                TextColumn::make('trip.destination')
                    ->label('To')
                    ->sortable()
                    ->limit(25),

                TextColumn::make('trip.customer.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable()
                    ->limit(20),

                TextColumn::make('trip.start_at')
                    ->label('Scheduled Time')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                TextColumn::make('trip.passenger_count')
                    ->label('Passengers')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'pending',
                        'info' => 'confirmed',
                        'warning' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'declined',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->sortable(),

                TextColumn::make('notes')
                    ->label('Special Instructions')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('trip.start_at')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                    ])
                    ->multiple(),
            ])
            ->actions([
                Action::make('confirm')
                    ->label('Confirm')
                    ->visible(fn (TripAssignment $record) => $record->status === 'pending')
                    ->action(fn (TripAssignment $record) => $record->update([
                        'status' => 'confirmed',
                        'confirmed_at' => now(),
                    ]))
                    ->requiresConfirmation()
                    ->icon('heroicon-o-check-circle'),

                Action::make('start')
                    ->label('Start')
                    ->visible(fn (TripAssignment $record) => $record->status === 'confirmed')
                    ->action(fn (TripAssignment $record) => $record->update([
                        'status' => 'in_progress',
                        'started_at' => now(),
                    ]))
                    ->requiresConfirmation()
                    ->color('warning')
                    ->icon('heroicon-o-play'),

                Action::make('complete')
                    ->label('Complete')
                    ->visible(fn (TripAssignment $record) => $record->status === 'in_progress')
                    ->action(fn (TripAssignment $record) => $record->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]))
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check'),

                Action::make('decline')
                    ->label('Decline')
                    ->visible(fn (TripAssignment $record) => $record->status === 'pending')
                    ->action(fn (TripAssignment $record) => $record->update([
                        'status' => 'declined',
                        'declined_at' => now(),
                    ]))
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-x-circle'),
            ])
            ->emptyStateHeading('No Assignments Yet')
            ->emptyStateDescription('You do not have any active trip assignments.');
    }
}
