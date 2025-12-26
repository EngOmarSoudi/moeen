<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('trip_id')
                    ->relationship('trip', 'id')
                    ->label(__('resources.expenses.fields.trip')),
                Select::make('driver_id')
                    ->relationship('driver', 'name')
                    ->label(__('resources.expenses.fields.driver')),
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'id')
                    ->label(__('resources.expenses.fields.vehicle')),
                TextInput::make('category')
                    ->label(__('resources.expenses.fields.category'))
                    ->required(),
                TextInput::make('amount')
                    ->label(__('resources.expenses.fields.amount'))
                    ->required()
                    ->numeric(),
                Textarea::make('description')
                    ->label(__('resources.expenses.fields.description'))
                    ->columnSpanFull(),
                DatePicker::make('expense_date')
                    ->label(__('resources.expenses.fields.expense_date'))
                    ->required(),
                TextInput::make('status')
                    ->label(__('resources.expenses.fields.status'))
                    ->required()
                    ->default('pending'),
                TextInput::make('submitted_by')
                    ->label(__('resources.expenses.fields.submitted_by'))
                    ->numeric(),
                TextInput::make('approved_by')
                    ->label(__('resources.expenses.fields.approved_by'))
                    ->numeric(),
                DateTimePicker::make('approved_at')
                    ->label(__('resources.expenses.fields.approved_at')),
                Textarea::make('rejection_reason')
                    ->label(__('resources.expenses.fields.rejection_reason'))
                    ->columnSpanFull(),
            ]);
    }
}
