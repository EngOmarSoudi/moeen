<?php

namespace App\Filament\Resources\VehicleTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VehicleTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('resources.vehicle_types.fields.name_en'))
                    ->required(),
                TextInput::make('name_ar')
                    ->label(__('resources.vehicle_types.fields.name_ar')),
                TextInput::make('capacity')
                    ->label(__('resources.vehicle_types.fields.capacity'))
                    ->required()
                    ->numeric(),
                Textarea::make('description')
                    ->label(__('resources.vehicle_types.fields.description'))
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label(__('resources.vehicle_types.fields.active'))
                    ->required(),
            ]);
    }
}
