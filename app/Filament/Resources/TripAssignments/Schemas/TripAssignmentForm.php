<?php

namespace App\Filament\Resources\TripAssignments\Schemas;

use App\Models\Driver;
use App\Models\Trip;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class TripAssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Trip & Driver Assignment')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // Trip selection with details
                                Select::make('trip_id')
                                    ->label('Trip')
                                    ->relationship('trip', 'code')
                                    ->searchable(['code', 'origin', 'destination'])
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->helperText('Select a trip to assign')
                                    ->columnSpan(1),

                                // Driver selection with intelligent filtering
                                Select::make('driver_id')
                                    ->label('Driver')
                                    ->options(fn (Get $get) => self::getSuggestedDrivers($get('trip_id')))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->rules(['required', 'integer', 'min:1'])
                                    ->helperText('Drivers are filtered by vehicle compatibility and availability')
                                    ->columnSpan(1),
                            ]),
                    ]),

                Section::make('Assignment Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'confirmed' => 'Confirmed',
                                        'in_progress' => 'In Progress',
                                        'completed' => 'Completed',
                                        'declined' => 'Declined',
                                        'canceled' => 'Canceled',
                                    ])
                                    ->default('pending')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('sequence_number')
                                    ->label('Sequence #')
                                    ->numeric()
                                    ->default(1)
                                    ->helperText('For multiple drivers on same trip')
                                    ->columnSpan(1),
                            ]),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Special instructions or notes for this assignment')
                            ->columnSpanFull(),
                    ]),

                Section::make('Timeline')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('assigned_at')
                                    ->label('Assigned At')
                                    ->default(now())
                                    ->columnSpan(1),

                                DateTimePicker::make('confirmed_at')
                                    ->label('Confirmed At')
                                    ->columnSpan(1),

                                DateTimePicker::make('started_at')
                                    ->label('Started At')
                                    ->columnSpan(1),

                                DateTimePicker::make('completed_at')
                                    ->label('Completed At')
                                    ->columnSpan(1),

                                DateTimePicker::make('declined_at')
                                    ->label('Declined At')
                                    ->columnSpan(2),
                            ]),
                    ]),
            ]);
    }

    /**
     * Get suggested drivers based on trip requirements and availability
     * Filters by vehicle type compatibility, time availability, and status
     */
    private static function getSuggestedDrivers(?int $tripId): array
    {
        if (!$tripId) {
            return [];
        }

        $trip = Trip::find($tripId);
        if (!$trip) {
            return [];
        }

        // Get all available drivers
        $drivers = Driver::with(['vehicles', 'vehicles.vehicleType'])
            ->where('status', 'available')
            ->get();

        $suggestions = [];
        $highPriority = []; // Compatible vehicle types
        $lowPriority = [];   // Available but no ideal vehicle

        foreach ($drivers as $driver) {
            // Check if driver has compatible vehicle type
            $hasCompatibleVehicle = false;

            if ($trip->vehicle_type_id) {
                $hasCompatibleVehicle = $driver->vehicles
                    ->where('vehicle_type_id', $trip->vehicle_type_id)
                    ->isNotEmpty();
            }

            // Check if driver is available during trip time
            $isTimeAvailable = self::isDriverAvailableForTime(
                $driver->id,
                $trip->start_at,
                $trip->completed_at
            );

            // Only add drivers who are time-available
            if ($isTimeAvailable) {
                $label = "{$driver->name} ({$driver->phone})";

                if ($hasCompatibleVehicle) {
                    // Add vehicle info to label
                    $vehicles = $driver->vehicles
                        ->where('vehicle_type_id', $trip->vehicle_type_id)
                        ->pluck('plate_number')
                        ->join(', ');
                    $label .= " - Vehicles: {$vehicles}";
                    $highPriority[$driver->id] = $label . ' ⭐';
                } else {
                    $lowPriority[$driver->id] = $label;
                }
            }
        }

        // Combine with high priority first
        return array_merge($highPriority, $lowPriority);
    }

    /**
     * Check if driver has no conflicting assignments during the trip time
     */
    private static function isDriverAvailableForTime(int $driverId, $startTime, $endTime): bool
    {
        $endTime = $endTime ?? now()->addHours(2);

        $conflictingAssignments = \App\Models\TripAssignment::where('driver_id', $driverId)
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->get()
            ->filter(function ($assignment) use ($startTime, $endTime) {
                $tripStart = $assignment->trip->start_at;
                $tripEnd = $assignment->trip->completed_at ?? $assignment->trip->start_at->addHours(2);

                // Check for time overlap
                return !($endTime <= $tripStart || $startTime >= $tripEnd);
            });

        return $conflictingAssignments->isEmpty();
    }
}