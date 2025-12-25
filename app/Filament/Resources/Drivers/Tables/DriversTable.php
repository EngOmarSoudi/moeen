<?php

namespace App\Filament\Resources\Drivers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DriversTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label(__('resources.drivers.fields.photo'))
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),
                TextColumn::make('name')
                    ->label(__('resources.drivers.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('phone')
                    ->label(__('resources.drivers.fields.phone'))
                    ->searchable()
                    ->copyable(),
                TextColumn::make('email')
                    ->label(__('resources.drivers.fields.email'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                BadgeColumn::make('status')
                    ->label(__('resources.drivers.fields.status'))
                    ->formatStateUsing(fn ($state) => __('resources.drivers.enums.' . $state))
                    ->colors([
                        'success' => 'available',
                        'warning' => 'busy',
                        'danger' => 'offline',
                        'secondary' => 'on_break',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'available',
                        'heroicon-o-clock' => 'busy',
                        'heroicon-o-x-circle' => 'offline',
                        'heroicon-o-pause-circle' => 'on_break',
                    ]),
                TextColumn::make('license_number')
                    ->label(__('resources.drivers.fields.license_number'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('license_expiry')
                    ->label(__('resources.drivers.fields.license_expiry'))
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable()
                    ->color(fn ($record) => 
                        $record->license_expiry && $record->license_expiry->isPast() ? 'danger' : null
                    ),
                TextColumn::make('rating')
                    ->label(__('resources.drivers.fields.rating'))
                    ->numeric(1)
                    ->suffix(' ★')
                    ->sortable()
                    ->alignCenter()
                    ->color('warning'),
                TextColumn::make('total_trips')
                    ->label(__('resources.drivers.fields.total_trips'))
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('user.name')
                    ->label(__('resources.drivers.fields.user'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'busy' => 'Busy',
                        'offline' => 'Offline',
                        'on_break' => 'On Break',
                    ])
                    ->multiple(),
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
            ->defaultSort('created_at', 'desc');
    }
}
