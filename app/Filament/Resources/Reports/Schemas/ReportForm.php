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
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('subject')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Select::make('trip_id')
                    ->relationship('trip', 'id'),
                Select::make('driver_id')
                    ->relationship('driver', 'name'),
                Select::make('customer_id')
                    ->relationship('customer', 'name'),
                TextInput::make('priority')
                    ->required()
                    ->default('medium'),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
                TextInput::make('assigned_to')
                    ->numeric(),
                TextInput::make('created_by')
                    ->numeric(),
                DateTimePicker::make('resolved_at'),
                Textarea::make('resolution_notes')
                    ->columnSpanFull(),
            ]);
    }
}
