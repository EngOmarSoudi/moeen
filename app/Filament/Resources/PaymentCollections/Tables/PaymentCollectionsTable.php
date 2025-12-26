<?php

namespace App\Filament\Resources\PaymentCollections\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentCollectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trip.id')
                    ->label(__('resources.payment_collections.fields.trip'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('driver.name')
                    ->label(__('resources.payment_collections.fields.driver'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('resources.payment_collections.fields.amount'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('received')
                    ->label(__('resources.payment_collections.fields.received'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('change')
                    ->label(__('resources.payment_collections.fields.change'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('method')
                    ->label(__('resources.payment_collections.fields.method'))
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('resources.payment_collections.fields.status'))
                    ->searchable(),
                TextColumn::make('confirmed_by')
                    ->label(__('resources.payment_collections.fields.confirmed_by'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('confirmed_at')
                    ->label(__('resources.payment_collections.fields.confirmed_at'))
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
