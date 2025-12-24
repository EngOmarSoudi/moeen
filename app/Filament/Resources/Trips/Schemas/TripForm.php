<?php

namespace App\Filament\Resources\Trips\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Group;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class TripForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                // Main Trip Information
                Section::make('Trip Details')
                    ->description('Basic trip information and classification')
                    ->icon('heroicon-o-map')
                    ->columns(2)
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('code')
                            ->label('Trip Code')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Auto-generated on save')
                            ->helperText('Unique identifier for this trip')
                            ->columnSpan(1),

                        Select::make('status')
                            ->label('Trip Status')
                            ->options([
                                'scheduled' => 'Scheduled',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('scheduled')
                            ->native(false)
                            ->columnSpan(1),

                        Select::make('trip_type_id')
                            ->label('Trip Type')
                            ->relationship('tripType', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),

                        Select::make('service_kind')
                            ->label('Service Type')
                            ->options([
                                'trip' => 'Transportation Trip',
                                'hotel_booking' => 'Hotel Booking',
                                'package' => 'Travel Package',
                            ])
                            ->required()
                            ->default('trip')
                            ->native(false)
                            ->columnSpan(1),


                    ]),

                // Quick Summary Card
                Section::make('Trip Summary')
                    ->description('Key metrics')
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('passenger_count')
                            ->label('Passengers')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(50)
                            ->suffix('person(s)')
                            ->helperText('Number of travelers'),

                        DateTimePicker::make('start_at')
                            ->label('Start Date & Time')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->minDate(now())
                            ->displayFormat('M d, Y H:i'),

                        DateTimePicker::make('completed_at')
                            ->label('Expected Completion')
                            ->native(false)
                            ->seconds(false)
                            ->displayFormat('M d, Y H:i')
                            ->hidden(fn (callable $get) => $get('status') === 'scheduled'),
                    ]),

                // Customer & Assignment
                Section::make('Customer & Assignment')
                    ->description('Assign customer and vehicle type to this trip')
                    ->icon('heroicon-o-users')
                    ->columns(2)
                    ->columnSpan(3)
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->searchable(['name', 'phone'])
                            ->preload()
                            ->required()
                            ->helperText('Search by customer name or phone number, or click "+" to create new customer')
                            ->createOptionForm([
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
                                Select::make('document_type')
                                    ->label('Document Type')
                                    ->options([
                                        'national_id' => 'National ID',
                                        'passport' => 'Passport',
                                        'residence_permit' => 'Residence Permit',
                                        'driver_license' => 'Driver License',
                                        'other' => 'Other',
                                    ])
                                    ->native(false),
                                TextInput::make('document_no')
                                    ->label('Document Number')
                                    ->maxLength(100),
                                TextInput::make('issuing_authority')
                                    ->label('Issuing Authority')
                                    ->maxLength(255),
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
                                Textarea::make('notes')
                                    ->label('General Notes')
                                    ->rows(2),
                                Textarea::make('special_case_note')
                                    ->label('Special Case Notes')
                                    ->rows(2),
                                TextInput::make('emergency_contact_name')
                                    ->label('Emergency Contact Name')
                                    ->maxLength(255),
                                TextInput::make('emergency_contact_phone')
                                    ->label('Emergency Contact Phone')
                                    ->tel()
                                    ->maxLength(20),
                            ])
                            ->columnSpan(1),

                        Select::make('agent_id')
                            ->label('Booking Agent')
                            ->relationship('agent', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Optional: Agent who booked this trip')
                            ->columnSpan(1),
                    
                        Select::make('vehicle_type_id')
                            ->label('Vehicle Type')
                            ->relationship('vehicleType', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Select the required vehicle type for this trip')
                            ->columnSpan(1),
                    
                        Select::make('travel_route_id')
                            ->label('Predefined Route')
                            ->relationship('travelRoute', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Select a saved route or enter custom below')
                            ->columnSpan(2),
                    ]),

                // Route Information
                Section::make('Route Information')
                    ->description('Specify pickup and drop-off locations')
                    ->icon('heroicon-o-map-pin')
                    ->columns(2)
                    ->columnSpan(3)
                    ->schema([
                        TextInput::make('origin')
                            ->label('Pickup Location')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., King Fahd Airport, Terminal 1')
                            ->columnSpan(1),

                        TextInput::make('destination')
                            ->label('Drop-off Location')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Riyadh Park Hotel, Olaya St')
                            ->columnSpan(1),

                        TextInput::make('hotel_name')
                            ->label('Hotel Name')
                            ->maxLength(255)
                            ->placeholder('If applicable')
                            ->columnSpan(2)
                            ->visible(fn (callable $get) => $get('service_kind') === 'hotel_booking'),
                    ]),

                // Pricing Section
                Section::make('Pricing & Payment')
                    ->description('Set trip pricing and discounts')
                    ->icon('heroicon-o-banknotes')
                    ->columns(3)
                    ->columnSpan(3)
                    ->schema([
                        TextInput::make('amount')
                            ->label('Base Amount')
                            ->required()
                            ->numeric()
                            ->prefix('SAR')
                            ->default(0)
                            ->minValue(0)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                                $set('final_amount', max(0, $state - ($get('discount') ?? 0))))
                            ->columnSpan(1),

                        TextInput::make('discount')
                            ->label('Discount')
                            ->numeric()
                            ->prefix('SAR')
                            ->default(0)
                            ->minValue(0)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                                $set('final_amount', max(0, ($get('amount') ?? 0) - $state)))
                            ->helperText('Promotional or special discount')
                            ->columnSpan(1),

                        TextInput::make('final_amount')
                            ->label('Final Amount')
                            ->required()
                            ->numeric()
                            ->prefix('SAR')
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->extraAttributes(['class' => 'font-bold text-lg'])
                            ->helperText('Amount after discount')
                            ->columnSpan(1),
                    ]),

                // Additional Notes
                Section::make('Additional Information')
                    ->description('Optional notes and special instructions')
                    ->icon('heroicon-o-document-text')
                    ->columns(1)
                    ->columnSpan(3)
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Textarea::make('notes')
                            ->label('General Notes')
                            ->rows(3)
                            ->placeholder('Any special instructions or requirements for this trip...')
                            ->columnSpanFull(),

                        Textarea::make('cancellation_reason')
                            ->label('Cancellation Reason')
                            ->rows(3)
                            ->placeholder('Explain why this trip was cancelled...')
                            ->columnSpanFull()
                            ->visible(fn (callable $get) => $get('status') === 'cancelled')
                            ->required(fn (callable $get) => $get('status') === 'cancelled'),
                    ]),
            ]);
    }
}
