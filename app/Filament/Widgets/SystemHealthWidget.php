<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Agent;
use App\Models\Trip;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

/**
 * System Health Widget
 * Shows overall system statistics and health
 * Visible to: Admin only
 */
class SystemHealthWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('admin');
    }

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        // Total system entities
        $totalCustomers = Customer::count();
        $totalDrivers = Driver::count();
        $totalVehicles = Vehicle::count();
        $totalAgents = Agent::count();
        
        // Active counts
        $activeDrivers = Driver::where('status', 'active')->count();
        $activeAgents = Agent::where('status', 'active')->count();
        
        // Growth metrics
        $newCustomersToday = Customer::whereDate('created_at', $today)->count();
        $newCustomersYesterday = Customer::whereDate('created_at', $yesterday)->count();
        $customerGrowth = $newCustomersYesterday > 0 
            ? (($newCustomersToday - $newCustomersYesterday) / $newCustomersYesterday) * 100 
            : 0;
        
        // Trip completion rate
        $completedTrips = Trip::where('status', 'completed')->count();
        $totalTrips = Trip::count();
        $completionRate = $totalTrips > 0 ? ($completedTrips / $totalTrips) * 100 : 0;
        
        // Average trip rating
        $avgTripRating = DB::table('trip_evaluations')
            ->join('evaluation_form_fields', 'trip_evaluations.field_id', '=', 'evaluation_form_fields.id')
            ->where('evaluation_form_fields.field_type', 'rating')
            ->avg('trip_evaluations.value');

        return [
            Stat::make(__('Total Customers'), number_format($totalCustomers))
                ->description(sprintf('%+d today (%+.1f%%)', $newCustomersToday, $customerGrowth))
                ->descriptionIcon($customerGrowth >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($customerGrowth >= 0 ? 'success' : 'danger')
                ->chart([120, 145, 160, 175, 190, 210, 230]),

            Stat::make(__('Fleet Status'), sprintf('%d / %d', $activeDrivers, $totalDrivers))
                ->description(sprintf('%d vehicles available', $totalVehicles))
                ->descriptionIcon('heroicon-o-truck')
                ->color('info'),

            Stat::make(__('Agent Network'), sprintf('%d active', $activeAgents))
                ->description(sprintf('of %d total agents', $totalAgents))
                ->descriptionIcon('heroicon-o-building-office')
                ->color('warning'),

            Stat::make(__('Completion Rate'), number_format($completionRate, 1) . '%')
                ->description(sprintf('%d of %d trips completed', $completedTrips, $totalTrips))
                ->descriptionIcon('heroicon-o-check-circle')
                ->color($completionRate >= 80 ? 'success' : ($completionRate >= 60 ? 'warning' : 'danger')),

            Stat::make(__('Service Quality'), $avgTripRating ? number_format($avgTripRating, 2) . ' ⭐' : 'N/A')
                ->description(__('Average trip rating'))
                ->descriptionIcon('heroicon-o-star')
                ->color('success'),
        ];
    }
}
