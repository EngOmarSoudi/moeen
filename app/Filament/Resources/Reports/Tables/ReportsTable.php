<?php

namespace App\Filament\Resources\Reports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference_no')
                    ->label(__('resources.reports.fields.reference_no'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('resources.reports.fields.type'))
                    ->searchable(),
                TextColumn::make('subject')
                    ->label(__('resources.reports.fields.subject'))
                    ->searchable(),
                TextColumn::make('trip.id')
                    ->label(__('resources.reports.fields.trip'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('driver.name')
                    ->label(__('resources.reports.fields.driver'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label(__('resources.reports.fields.customer'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('priority')
                    ->label(__('resources.reports.fields.priority'))
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('resources.reports.fields.status'))
                    ->searchable(),
                TextColumn::make('assigned_to')
                    ->label(__('resources.reports.fields.assigned_to'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_by')
                    ->label(__('resources.reports.fields.created_by'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('resolved_at')
                    ->label(__('resources.reports.fields.resolved_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
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
