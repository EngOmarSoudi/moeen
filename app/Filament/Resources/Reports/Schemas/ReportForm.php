<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('reference_no')
                    ->label(__('resources.reports.fields.reference_no'))
                    ->required(),
                TextInput::make('type')
                    ->label(__('resources.reports.fields.type'))
                    ->required(),
                TextInput::make('subject')
                    ->label(__('resources.reports.fields.subject'))
                    ->required(),
                Textarea::make('description')
                    ->label(__('resources.reports.fields.description'))
                    ->required()
                    ->columnSpanFull(),
                Select::make('trip_id')
                    ->relationship('trip', 'id')
                    ->label(__('resources.reports.fields.trip')),
                Select::make('driver_id')
                    ->relationship('driver', 'name')
                    ->label(__('resources.reports.fields.driver')),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->label(__('resources.reports.fields.customer')),
                TextInput::make('priority')
                    ->label(__('resources.reports.fields.priority'))
                    ->required()
                    ->default('medium'),
                TextInput::make('status')
                    ->label(__('resources.reports.fields.status'))
                    ->required()
                    ->default('open'),
                TextInput::make('assigned_to')
                    ->label(__('resources.reports.fields.assigned_to'))
                    ->numeric(),
                TextInput::make('created_by')
                    ->label(__('resources.reports.fields.created_by'))
                    ->numeric(),
                DateTimePicker::make('resolved_at')
                    ->label(__('resources.reports.fields.resolved_at')),
                Textarea::make('resolution_notes')
                    ->label(__('resources.reports.fields.resolution_notes'))
                    ->columnSpanFull(),
            ]);
    }
}
