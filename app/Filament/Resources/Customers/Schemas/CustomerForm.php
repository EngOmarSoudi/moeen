<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20),
                                TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('nationality')
                                    ->label('Nationality')
                                    ->maxLength(100),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Document Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('document_type')
                                    ->label('Document Type')
                                    ->options([
                                        'id_card' => 'National ID Card',
                                        'passport' => 'Passport',
                                        'residence' => 'Residence Permit',
                                        'other' => 'Other',
                                    ])
                                    ->native(false),
                                TextInput::make('document_no')
                                    ->label('Document Number')
                                    ->maxLength(100),
                                TextInput::make('issuing_authority')
                                    ->label('Issuing Authority')
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columns(1),

                Section::make('Assignment & Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status_id')
                                    ->label('Customer Status')
                                    ->relationship('status', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('agent_id')
                                    ->label('Assigned Agent')
                                    ->relationship('agent', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Notes & Special Cases')
                    ->schema([
                        Textarea::make('notes')
                            ->label('General Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('special_case_note')
                            ->label('Special Case Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
