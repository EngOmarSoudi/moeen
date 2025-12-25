<?php

namespace App\Filament\Resources\Trips\Tables;

use Filament\Actions\Action;
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
                    ->label(__('resources.trips.fields.code'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                BadgeColumn::make('status')
                    ->label(__('resources.trips.fields.status'))
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
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'scheduled' => __('resources.trips.enums.scheduled'),
                        'in_progress' => __('resources.trips.enums.in_progress'),
                        'completed' => __('resources.trips.enums.completed'),
                        'cancelled' => __('resources.trips.enums.cancelled'),
                        default => $state,
                    }),
                TextColumn::make('customer.name')
                    ->label(__('resources.trips.fields.customer'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('origin')
                    ->label(__('resources.trips.fields.origin'))
                    ->searchable()
                    ->limit(20),
                TextColumn::make('destination')
                    ->label(__('resources.trips.fields.destination'))
                    ->searchable()
                    ->limit(20),
                TextColumn::make('start_at')
                    ->label(__('resources.trips.fields.start_at'))
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                TextColumn::make('passenger_count')
                    ->label(__('resources.trips.fields.passenger_count'))
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('final_amount')
                    ->label(__('resources.trips.fields.final_amount'))
                    ->money('SAR') // Ideally should handle currency formatting too but SAR is fixed here
                    ->sortable()
                    ->alignEnd(),
                BadgeColumn::make('service_kind')
                    ->label(__('resources.trips.fields.service_kind'))
                    ->colors([
                        'primary' => 'airport',
                        'warning' => 'hotel',
                        'success' => 'city_tour',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'airport' => __('resources.trips.enums.airport'),
                        'hotel' => __('resources.trips.enums.hotel'),
                        'city_tour' => __('resources.trips.enums.city_tour'),
                        default => $state,
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('agent.name')
                    ->label(__('Agent'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('resources.trips.fields.status'))
                    ->options([
                        'scheduled' => __('resources.trips.enums.scheduled'),
                        'in_progress' => __('resources.trips.enums.in_progress'),
                        'completed' => __('resources.trips.enums.completed'),
                        'cancelled' => __('resources.trips.enums.cancelled'),
                    ])
                    ->multiple(),
                SelectFilter::make('service_kind')
                    ->label(__('resources.trips.fields.service_kind'))
                    ->options([
                        'airport' => __('resources.trips.enums.airport'),
                        'hotel' => __('resources.trips.enums.hotel'),
                        'city_tour' => __('resources.trips.enums.city_tour'),
                    ]),
                TrashedFilter::make()
                    ->label(__('Trashed')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('print')
                    ->label(__('Print Trip'))
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn ($record) => route('trips.print', ['trip' => $record, 'print' => true]))
                    ->openUrlInNewTab(),
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
