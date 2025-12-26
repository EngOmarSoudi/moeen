<?php

namespace App\Filament\Resources\RouteTemplates;

use App\Filament\Resources\RouteTemplates\Pages\CreateRouteTemplate;
use App\Filament\Resources\RouteTemplates\Pages\EditRouteTemplate;
use App\Filament\Resources\RouteTemplates\Pages\ListRouteTemplates;
use App\Models\RouteTemplate;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class RouteTemplateResource extends Resource
{
    protected static ?string $model = RouteTemplate::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    public static function getModelLabel(): string
    {
        return __('resources.route_templates.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.route_templates.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('resources.route_templates.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('resources.navigation.operations');
    }

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'origin_city';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make(__('resources.route_templates.sections.info'))
                    ->description(__('Define the city pair for this pricing template'))
                    ->columns(2)
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('origin_city')
                            ->label(__('resources.route_templates.fields.origin_city_en'))
                            ->placeholder('e.g., Jeddah')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('origin_city_ar')
                            ->label(__('resources.route_templates.fields.origin_city_ar'))
                            ->placeholder('مثال: جدة')
                            ->maxLength(100),

                        TextInput::make('destination_city')
                            ->label(__('resources.route_templates.fields.destination_city_en'))
                            ->placeholder('e.g., Mecca')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('destination_city_ar')
                            ->label(__('resources.route_templates.fields.destination_city_ar'))
                            ->placeholder('مثال: مكة')
                            ->maxLength(100),
                    ]),

                Section::make(__('resources.route_templates.sections.pricing'))
                    ->description(__('Set the base price for this route'))
                    ->columns(2)
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('base_price')
                            ->label(__('resources.route_templates.fields.base_price'))
                            ->required()
                            ->numeric()
                            ->prefix('SAR')
                            ->minValue(0)
                            ->step(0.01)
                            ->placeholder('e.g., 300.00'),

                        Select::make('vehicle_type_id')
                            ->label(__('resources.route_templates.fields.vehicle_type'))
                            ->relationship('vehicleType', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder(__('All vehicle types'))
                            ->helperText(__('Leave empty to apply to all vehicle types')),

                        Textarea::make('description')
                            ->label(__('resources.route_templates.fields.description'))
                            ->placeholder(__('Optional notes about this route pricing...'))
                            ->columnSpanFull()
                            ->rows(2),

                        Toggle::make('is_active')
                            ->label(__('resources.route_templates.fields.active'))
                            ->default(true)
                            ->helperText(__('Inactive templates won\'t be available for selection')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('origin_city')
                    ->label(__('resources.trips.fields.origin'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('destination_city')
                    ->label(__('resources.trips.fields.destination'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('base_price')
                    ->label(__('resources.route_templates.fields.base_price'))
                    ->money('SAR')
                    ->sortable(),

                TextColumn::make('vehicleType.name')
                    ->label(__('resources.route_templates.fields.vehicle_type'))
                    ->placeholder(__('All Types'))
                    ->badge()
                    ->color('gray'),

                IconColumn::make('is_active')
                    ->label(__('resources.route_templates.fields.active'))
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status'),

                SelectFilter::make('vehicle_type_id')
                    ->label('Vehicle Type')
                    ->relationship('vehicleType', 'name'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('origin_city');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRouteTemplates::route('/'),
            'create' => CreateRouteTemplate::route('/create'),
            'edit' => EditRouteTemplate::route('/{record}/edit'),
        ];
    }
}
