<?php

namespace App\Filament\Resources\TripTypes;

use App\Filament\Resources\TripTypes\Pages\CreateTripType;
use App\Filament\Resources\TripTypes\Pages\EditTripType;
use App\Filament\Resources\TripTypes\Pages\ListTripTypes;
use App\Filament\Resources\TripTypes\Schemas\TripTypeForm;
use App\Filament\Resources\TripTypes\Tables\TripTypesTable;
use App\Models\TripType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TripTypeResource extends Resource
{
    protected static ?string $model = TripType::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TripTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TripTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTripTypes::route('/'),
            'create' => CreateTripType::route('/create'),
            'edit' => EditTripType::route('/{record}/edit'),
        ];
    }
}
