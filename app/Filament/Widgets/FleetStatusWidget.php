<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FleetStatusWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $totalDrivers = Driver::count();
        $availableDrivers = Driver::where('status', 'available')->count();
        $totalVehicles = Vehicle::count();
        $activeVehicles = Vehicle::where('status', 'active')->count();
        
        $driverUtilization = $totalDrivers > 0 
            ? round(($totalDrivers - $availableDrivers) / $totalDrivers * 100, 1) 
            : 0;
        
        $vehicleUtilization = $totalVehicles > 0 
            ? round($activeVehicles / $totalVehicles * 100, 1) 
            : 0;

        return [
            Stat::make('Total Drivers', $totalDrivers)
                ->description("{$availableDrivers} available")
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Driver Utilization', "{$driverUtilization}%")
                ->description('Drivers currently working')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($driverUtilization > 70 ? 'success' : 'warning'),

            Stat::make('Total Vehicles', $totalVehicles)
                ->description("{$activeVehicles} active")
                ->descriptionIcon('heroicon-o-truck')
                ->color('info'),

            Stat::make('Vehicle Utilization', "{$vehicleUtilization}%")
                ->description('Vehicles in use')
                ->descriptionIcon('heroicon-o-chart-pie')
                ->color($vehicleUtilization > 70 ? 'success' : 'warning'),
        ];
    }
}
