<?php

namespace App\Filament\Resources\Staff\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StaffTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('resources.staff.fields.user'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('employee_id')
                    ->label(__('resources.staff.fields.employee_id'))
                    ->searchable(),
                TextColumn::make('department')
                    ->label(__('resources.staff.fields.department'))
                    ->searchable(),
                TextColumn::make('job_title')
                    ->label(__('resources.staff.fields.job_title'))
                    ->searchable(),
                TextColumn::make('hired_at')
                    ->label(__('resources.staff.fields.hired_at'))
                    ->date()
                    ->sortable(),
                TextColumn::make('birth_date')
                    ->label(__('resources.staff.fields.birth_date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('salary')
                    ->label(__('resources.staff.fields.salary'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('emergency_contact')
                    ->label(__('resources.staff.fields.emergency_contact'))
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('resources.staff.fields.status'))
                    ->searchable(),
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
                    ->label(__('Deleted At'))
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
