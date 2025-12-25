<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('resources.customers.fields.name'))
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('resources.customers.fields.phone'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('resources.customers.fields.email'))
                    ->searchable(),
                TextColumn::make('nationality')
                    ->label(__('resources.customers.fields.nationality'))
                    ->searchable(),
                TextColumn::make('document_type')
                    ->label(__('resources.customers.fields.document_type'))
                    ->formatStateUsing(fn ($state) => __('resources.customers.enums.' . $state))
                    ->searchable(),
                TextColumn::make('document_no')
                    ->label(__('resources.customers.fields.document_no'))
                    ->searchable(),
                TextColumn::make('issuing_authority')
                    ->label(__('resources.customers.fields.issuing_authority'))
                    ->searchable(),
                TextColumn::make('status.name')
                    ->label(__('resources.customers.fields.status'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('agent.name')
                    ->label(__('resources.customers.fields.agent'))
                    ->numeric()
                    ->sortable(),
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
