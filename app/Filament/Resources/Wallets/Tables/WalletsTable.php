<?php

namespace App\Filament\Resources\Wallets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WalletsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('walletable_type')
                    ->label('Owner Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->sortable(),
                TextColumn::make('walletable.name')
                    ->label('Owner')
                    ->searchable(),
                TextColumn::make('balance')
                    ->label('Balance')
                    ->money('SAR')
                    ->sortable()
                    ->color(fn ($record) => $record->balance < 0 ? 'danger' : 'success'),
                TextColumn::make('total_debt')
                    ->label('Total Debt')
                    ->money('SAR')
                    ->sortable()
                    ->color('danger'),
                TextColumn::make('total_collected')
                    ->label('Total Collected')
                    ->money('SAR')
                    ->sortable()
                    ->color('success'),
                TextColumn::make('budget_limit')
                    ->label('Budget Limit')
                    ->money('SAR')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('budget_used')
                    ->label('Budget Used')
                    ->money('SAR')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('walletable_type')
                    ->label('Owner Type')
                    ->options([
                        'App\\Models\\Customer' => 'Customer',
                        'App\\Models\\Driver' => 'Driver',
                        'App\\Models\\Agent' => 'Agent',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
