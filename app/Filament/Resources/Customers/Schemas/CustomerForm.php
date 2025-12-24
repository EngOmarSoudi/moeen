<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('nationality'),
                TextInput::make('document_type'),
                TextInput::make('document_no'),
                TextInput::make('issuing_authority'),
                Select::make('status_id')
                    ->relationship('status', 'name'),
                Select::make('agent_id')
                    ->relationship('agent', 'name'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Textarea::make('special_case_note')
                    ->columnSpanFull(),
            ]);
    }
}
