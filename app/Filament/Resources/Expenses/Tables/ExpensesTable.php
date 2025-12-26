<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trip.id')
                    ->label(__('resources.expenses.fields.trip'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('driver.name')
                    ->label(__('resources.expenses.fields.driver'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('vehicle.id')
                    ->label(__('resources.expenses.fields.vehicle'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('category')
                    ->label(__('resources.expenses.fields.category'))
                    ->searchable(),
                TextColumn::make('amount')
                    ->label(__('resources.expenses.fields.amount'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expense_date')
                    ->label(__('resources.expenses.fields.expense_date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('resources.expenses.fields.status'))
                    ->searchable(),
                TextColumn::make('submitted_by')
                    ->label(__('resources.expenses.fields.submitted_by'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_by')
                    ->label(__('resources.expenses.fields.approved_by'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->label(__('resources.expenses.fields.approved_at'))
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
