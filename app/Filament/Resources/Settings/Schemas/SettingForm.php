<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label(__('resources.settings.fields.key'))
                    ->required(),
                Textarea::make('value')
                    ->label(__('resources.settings.fields.value'))
                    ->columnSpanFull(),
                TextInput::make('group')
                    ->label(__('resources.settings.fields.group'))
                    ->required()
                    ->default('general'),
                TextInput::make('type')
                    ->label(__('resources.settings.fields.type'))
                    ->required()
                    ->default('string'),
            ]);
    }
}
