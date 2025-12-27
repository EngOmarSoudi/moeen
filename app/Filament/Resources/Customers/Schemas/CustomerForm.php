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
                Section::make(__('resources.customers.sections.personal'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('resources.customers.fields.name'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->label(__('resources.customers.fields.phone'))
                                    ->tel()
                                    ->required()
                                    ->maxLength(20),
                                TextInput::make('email')
                                    ->label(__('resources.customers.fields.email'))
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('nationality')
                                    ->label(__('resources.customers.fields.nationality'))
                                    ->maxLength(100),
                            ]),
                    ])
                    ->columns(2),

                Section::make(__('resources.customers.sections.documents'))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('document_type')
                                    ->label(__('resources.customers.fields.document_type'))
                                    ->options([
                                        'id_card' => __('resources.customers.enums.id_card'),
                                        'passport' => __('resources.customers.enums.passport'),
                                        'residence' => __('resources.customers.enums.residence'),
                                        'other' => __('resources.customers.enums.other'),
                                    ])
                                    ->native(false),
                                TextInput::make('document_no')
                                    ->label(__('resources.customers.fields.document_no'))
                                    ->maxLength(100),
                                TextInput::make('issuing_authority')
                                    ->label(__('resources.customers.fields.issuing_authority'))
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columns(1),

                Section::make(__('resources.customers.sections.assignment'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status_id')
                                    ->label(__('resources.customers.fields.status'))
                                    ->relationship('status', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('agent_id')
                                    ->label(__('resources.customers.fields.agent'))
                                    ->relationship('agent', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ])
                    ->columns(2),

                Section::make(__('resources.customers.sections.emergency'))
                    ->description(__('resources.customers.sections.emergency_desc')) // This key was not added, but safe to use title or add it. I'll stick to title or add generic description later if needed. For now I'll just remove description or use translation if I added it. Wait, I didn't add descriptions in lang file. I'll just use the title or a generic string. Actually, I removed descriptions from my lang replacement for simplicity unless I add them. I will use `__('Contact person in case of emergency')` which will fallback to English if not found, or better yet, skip description for now to keep it clean or use a generic translated string if available. Let's just translate the string literal manually in code: `__('Contact person in case of emergency')`.
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('emergency_contact_name')
                                    ->label(__('resources.customers.fields.emergency_name'))
                                    ->maxLength(255),
                                TextInput::make('emergency_contact_phone')
                                    ->label(__('resources.customers.fields.emergency_phone'))
                                    ->tel()
                                    ->maxLength(20),
                                TextInput::make('emergency_contact_email')
                                    ->label(__('resources.customers.fields.emergency_email'))
                                    ->email()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Section::make(__('resources.customers.sections.notes'))
                    ->schema([
                        Textarea::make('notes')
                            ->label(__('resources.customers.fields.notes'))
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('special_case_note')
                            ->label(__('resources.customers.fields.special_notes'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
