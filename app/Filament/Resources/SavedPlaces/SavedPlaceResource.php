<?php

namespace App\Filament\Resources\SavedPlaces;

use App\Filament\Resources\SavedPlaces\Pages\CreateSavedPlace;
use App\Filament\Resources\SavedPlaces\Pages\EditSavedPlace;
use App\Filament\Resources\SavedPlaces\Pages\ListSavedPlaces;
use App\Filament\Resources\SavedPlaces\Schemas\SavedPlaceForm;
use App\Filament\Resources\SavedPlaces\Tables\SavedPlacesTable;
use App\Models\SavedPlace;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SavedPlaceResource extends Resource
{
    protected static ?string $model = SavedPlace::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SavedPlaceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SavedPlacesTable::configure($table);
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
            'index' => ListSavedPlaces::route('/'),
            'create' => CreateSavedPlace::route('/create'),
            'edit' => EditSavedPlace::route('/{record}/edit'),
        ];
    }
}
