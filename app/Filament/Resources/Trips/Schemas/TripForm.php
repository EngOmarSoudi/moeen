<?php

namespace App\Filament\Resources\Trips\Schemas;

use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Group;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Schemas\Schema;

class TripForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Main Trip Information
                Section::make(__('resources.trips.label'))
                    ->description(__('details'))
                    ->icon('heroicon-o-map')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('code')
                                    ->label(__('resources.trips.fields.code'))
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->placeholder(__('Auto-generated on save')),

                                Select::make('status')
                                    ->label(__('resources.trips.fields.status'))
                                    ->options([
                                        'scheduled' => __('resources.trips.enums.scheduled'),
                                        'in_progress' => __('resources.trips.enums.in_progress'),
                                        'completed' => __('resources.trips.enums.completed'),
                                        'cancelled' => __('resources.trips.enums.cancelled'),
                                    ])
                                    ->required()
                                    ->default('scheduled')
                                    ->native(false),

                                Select::make('service_kind')
                                    ->label(__('resources.trips.fields.service_kind'))
                                    ->options([
                                        'airport' => __('resources.trips.enums.airport'),
                                        'hotel' => __('resources.trips.enums.hotel'),
                                        'city_tour' => __('resources.trips.enums.city_tour'),
                                    ])
                                    ->required()
                                    ->default('airport')
                                    ->native(false)
                                    ->reactive(),

                                Select::make('vehicle_type_id')
                                    ->label(__('resources.trips.fields.vehicle_type'))
                                    ->relationship('vehicleType', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),
                    ]),

                // Quick Summary Card
                Section::make(__('Summary'))
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('passenger_count')
                                    ->label(__('resources.trips.fields.passenger_count'))
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->maxValue(50),

                                DateTimePicker::make('start_at')
                                    ->label(__('resources.trips.fields.start_at'))
                                    ->required()
                                    ->native(false)
                                    ->seconds(false)
                                    ->minDate(now())
                                    ->displayFormat('M d, Y H:i'),

                                DateTimePicker::make('completed_at')
                                    ->label(__('Expected Completion'))
                                    ->native(false)
                                    ->seconds(false)
                                    ->displayFormat('M d, Y H:i')
                                    ->hidden(fn (callable $get) => $get('status') === 'scheduled'),
                            ]),
                    ]),

                // Customer & Assignment
                Section::make(__('resources.customers.label') . ' & ' . __('Assignment'))
                    ->description(__('Assign customer and vehicle type to this trip'))
                    ->icon('heroicon-o-users')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('customer_id')
                                    ->label(__('resources.trips.fields.customer'))
                                    ->relationship('customer', 'name')
                                    ->searchable(['name', 'phone'])
                                    ->preload()
                                    ->required()
                                    ->helperText(__('Search by customer name or phone number, or click "+" to create new customer'))
                                    ->live()
                                    ->afterStateUpdated(function (callable $set, $state) {
                                        if ($state) {
                                            $customer = \App\Models\Customer::with(['status', 'agent'])->find($state);
                                            if ($customer) {
                                                // Populate display fields
                                                $set('customer_phone', $customer->phone);
                                                $set('customer_email', $customer->email);
                                                $set('customer_nationality', $customer->nationality);
                                                $set('customer_document_type', $customer->document_type);
                                                $set('customer_document_no', $customer->document_no);
                                                $set('customer_issuing_authority', $customer->issuing_authority);
                                                $set('customer_status_id', $customer->status?->name);
                                                $set('customer_agent_id', $customer->agent?->name);
                                                $set('customer_notes', $customer->notes);
                                                $set('customer_special_case_note', $customer->special_case_note);
                                                $set('customer_emergency_contact_name', $customer->emergency_contact_name);
                                                $set('customer_emergency_contact_phone', $customer->emergency_contact_phone);
                                                $set('customer_emergency_contact_email', $customer->emergency_contact_email);
                                            }
                                        }
                                    })
                                    ->createOptionForm([
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
                                        Select::make('document_type')
                                            ->label(__('resources.customers.fields.document_type'))
                                            ->options([
                                                'national_id' => __('National ID'),
                                                'passport' => __('Passport'),
                                                'residence_permit' => __('Residence Permit'),
                                                'driver_license' => __('Driver License'),
                                                'other' => __('Other'),
                                            ])
                                            ->native(false),
                                        TextInput::make('document_no')
                                            ->label(__('Document Number'))
                                            ->maxLength(100),
                                        TextInput::make('issuing_authority')
                                            ->label(__('Issuing Authority'))
                                            ->maxLength(255),
                                        Select::make('status_id')
                                            ->label(__('resources.customers.fields.status'))
                                            ->relationship('status', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Forms\Components\TextInput::make('agent_display')
                                            ->label(__('Assigned Agent'))
                                            ->default(function () {
                                                $user = auth()->user();
                                                
                                                // Check if user has 'agent' role
                                                if ($user->hasRole('agent')) {
                                                    $agent = \App\Models\Agent::where('email', $user->email)->first();
                                                    return $agent ? $agent->name : __('Agent Not Found');
                                                }
                                                
                                                // For admin or other roles
                                                return __('Main Company');
                                            })
                                            ->disabled()
                                            ->dehydrated(false),

                                        Forms\Components\Hidden::make('agent_id')
                                            ->default(function () {
                                                $user = auth()->user();
                                                
                                                // Check if user has 'agent' role
                                                if ($user->hasRole('agent')) {
                                                    return \App\Models\Agent::where('email', $user->email)->first()?->id;
                                                }
                                                
                                                // For admin role, return null
                                                return null;
                                            }),
                                        Textarea::make('notes')
                                            ->label(__('General Notes'))
                                            ->rows(2),
                                        Textarea::make('special_case_note')
                                            ->label(__('Special Case Notes'))
                                            ->rows(2),
                                        TextInput::make('emergency_contact_name')
                                            ->label(__('Emergency Contact Name'))
                                            ->maxLength(255),
                                        TextInput::make('emergency_contact_phone')
                                            ->label(__('Emergency Contact Phone'))
                                            ->tel()
                                            ->maxLength(20),
                                        TextInput::make('emergency_contact_email')
                                            ->label(__('Emergency Contact Email'))
                                            ->email()
                                            ->maxLength(255),
                                    ]),

                                Forms\Components\TextInput::make('agent_display')
                                    ->label(__('Booking Agent'))
                                    ->default(function () {
                                        $user = auth()->user();
                                        
                                        // Check if user has 'agent' role
                                        if ($user->hasRole('agent')) {
                                            $agent = \App\Models\Agent::where('email', $user->email)->first();
                                            return $agent ? $agent->name : __('Agent Not Found');
                                        }
                                        
                                        // For admin or other roles, show Main Company
                                        return __('Main Company');
                                    })
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->helperText(function () {
                                        $user = auth()->user();
                                        if ($user->hasRole('agent')) {
                                            return __('You are booking as an agent');
                                        }
                                        return __('You are booking for the main company');
                                    }),

                                Forms\Components\TextInput::make('agent_id')
                                    ->label(__('Agent ID'))
                                    ->default(function () {
                                        $user = auth()->user();
                                        
                                        // Check if user has 'agent' role
                                        if ($user->hasRole('agent')) {
                                            $agent = \App\Models\Agent::where('email', $user->email)->first();
                                            return $agent?->id; // Return agent ID if found
                                        }
                                        
                                        // For admin role, return null (main company - no specific agent)
                                        return null;
                                    })
                                    ->dehydrated(true)
                                    ->readOnly()
                                    ->hidden()
                                    ->afterStateHydrated(function ($state, $set) {
                                        // Ensure agent_id is set correctly even after form load
                                        $user = auth()->user();
                                        if ($user->hasRole('agent') && !$state) {
                                            $agent = \App\Models\Agent::where('email', $user->email)->first();
                                            if ($agent) {
                                                $set('agent_id', $agent->id);
                                            }
                                        }
                                    }),
                            ]),
                    ]),

                // Customer Details Section
                Section::make(__('Customer Details'))
                    ->description(__('Automatically populated when customer is selected'))
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('customer_phone')
                                    ->label(__('resources.customers.fields.phone'))
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('customer_email')
                                    ->label(__('resources.customers.fields.email'))
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('customer_nationality')
                                    ->label(__('resources.customers.fields.nationality'))
                                    ->disabled()
                                    ->dehydrated(),
                                Select::make('customer_document_type')
                                    ->label(__('resources.customers.fields.document_type'))
                                    ->options([
                                        'national_id' => __('National ID'),
                                        'passport' => __('Passport'),
                                        'residence_permit' => 'Residence Permit',
                                        'driver_license' => 'Driver License',
                                        'other' => 'Other',
                                    ])
                                    ->native(false)
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('customer_document_no')
                                    ->label('Document Number')
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('customer_issuing_authority')
                                    ->label('Issuing Authority')
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('customer_status')
                                    ->label('Customer Status')
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('customer_agent_name')
                                    ->label('Assigned Agent')
                                    ->disabled()
                                    ->dehydrated(),
                                Textarea::make('customer_notes')
                                    ->label('General Notes')
                                    ->rows(2)
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpanFull(),
                                Textarea::make('customer_special_case_note')
                                    ->label('Special Case Notes')
                                    ->rows(2)
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpanFull(),
                                TextInput::make('customer_emergency_contact_name')
                                    ->label('Emergency Contact Name')
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('customer_emergency_contact_phone')
                                    ->label('Emergency Contact Phone')
                                    ->tel()
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('customer_emergency_contact_email')
                                    ->label('Emergency Contact Email')
                                    ->disabled()
                                    ->dehydrated(),
                            ]),
                    ]),
                // Route Information
                Section::make(__('resources.routes.label'))
                    ->description(__('Specify pickup and drop-off locations'))
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('travel_route_id')
                                    ->label(__('Predefined Route'))
                                    ->relationship('travelRoute', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $route = \App\Models\TravelRoute::find($state);
                                            if ($route) {
                                                $set('origin', $route->origin);
                                                $set('origin_lat', $route->origin_lat);
                                                $set('origin_lng', $route->origin_lng);
                                                $set('destination', $route->destination);
                                                $set('destination_lat', $route->destination_lat);
                                                $set('destination_lng', $route->destination_lng);
                                            }
                                        }
                                    })
                                    ->helperText(__('Optional: Select a predefined route to auto-populate locations'))
                                    ->columnSpanFull(),

                                \App\Filament\Forms\Components\TripLocationPicker::make('origin')
                                    ->label(__('resources.trips.fields.origin'))
                                    ->required(),

                                \App\Filament\Forms\Components\TripLocationPicker::make('destination')
                                    ->label(__('resources.trips.fields.destination'))
                                    ->required(),

                                // Coordinate fields - visible for debugging, will hide with CSS
                                Forms\Components\TextInput::make('origin_lat')
                                    ->label(__('Origin Latitude'))
                                    ->dehydrated(true)
                                    ->default(null)
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'coordinate-field'])
                                    ->columnSpan(1),
                                    
                                Forms\Components\TextInput::make('origin_lng')
                                    ->label(__('Origin Longitude'))
                                    ->dehydrated(true)
                                    ->default(null)
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'coordinate-field'])
                                    ->columnSpan(1),
                                    
                                Forms\Components\TextInput::make('destination_lat')
                                    ->label(__('Destination Latitude'))
                                    ->dehydrated(true)
                                    ->default(null)
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'coordinate-field'])
                                    ->columnSpan(1),
                                    
                                Forms\Components\TextInput::make('destination_lng')
                                    ->label(__('Destination Longitude'))
                                    ->dehydrated(true)
                                    ->default(null)
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'coordinate-field'])
                                    ->columnSpan(1),

                                TextInput::make('hotel_name')
                                    ->label(__('resources.trips.fields.hotel_name'))
                                    ->maxLength(255)
                                    ->placeholder(__('If applicable'))
                                    ->columnSpanFull()
                                    ->visible(fn (callable $get) => $get('service_kind') === 'hotel_booking'),
                            ]),
                    ]),

                // Pricing Section
                Section::make(__('Pricing & Payment'))
                    ->description(__('Set trip pricing and discounts'))
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('amount')
                                    ->label(__('resources.trips.fields.amount'))
                                    ->required()
                                    ->numeric()
                                    ->prefix(__('SAR'))
                                    ->default(0)
                                    ->minValue(0)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                                        $set('final_amount', max(0, $state - ($get('discount') ?? 0)))),

                                TextInput::make('discount')
                                    ->label(__('Discount'))
                                    ->numeric()
                                    ->prefix(__('SAR'))
                                    ->default(0)
                                    ->minValue(0)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                                        $set('final_amount', max(0, ($get('amount') ?? 0) - $state)))
                                    ->dehydrateStateUsing(fn ($state) => $state ?? 0)
                                    ->helperText(__('Promotional or special discount')),

                                TextInput::make('final_amount')
                                    ->label(__('resources.trips.fields.final_amount'))
                                    ->required()
                                    ->numeric()
                                    ->prefix(__('SAR'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(0)
                                    ->dehydrateStateUsing(fn ($state) => $state ?? 0)
                                    ->extraAttributes(['class' => 'font-bold text-lg'])
                                    ->helperText(__('Amount after discount')),
                            ]),
                    ]),

                // Additional Notes
                Section::make(__('Additional Information'))
                    ->description(__('Optional notes and special instructions'))
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Textarea::make('notes')
                                    ->label(__('General Notes'))
                                    ->rows(3)
                                    ->placeholder(__('Any special instructions or requirements for this trip...'))
                                    ->columnSpanFull(),

                                Textarea::make('cancellation_reason')
                                    ->label(__('Cancellation Reason'))
                                    ->rows(3)
                                    ->placeholder(__('Explain why this trip was cancelled...'))
                                    ->columnSpanFull()
                                    ->visible(fn (callable $get) => $get('status') === 'cancelled')
                                    ->required(fn (callable $get) => $get('status') === 'cancelled'),
                            ]),
                    ]),
            ]);
    }
}
