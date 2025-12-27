<?php

namespace App\Filament\Widgets;

use App\Models\Agent;
use App\Models\Trip;
use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * My Agent Stats Widget
 * Shows stats for the logged-in agent's performance
 * Visible to: Agents only
 */
class MyAgentStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('agent');
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $agent = Agent::where('email', $user->email)->first();

        if (!$agent) {
            return [
                Stat::make(__('Agent Profile'), __('Not Found'))
                    ->description(__('Please contact administrator'))
                    ->descriptionIcon('heroicon-o-exclamation-triangle')
                    ->color('danger'),
            ];
        }

        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        $thisWeek = now()->startOfWeek();

        // My bookings
        $myBookingsToday = Trip::where('agent_id', $agent->id)
            ->whereDate('start_at', $today)
            ->count();
        
        $myBookingsThisWeek = Trip::where('agent_id', $agent->id)
            ->whereDate('start_at', '>=', $thisWeek)
            ->count();
        
        $myBookingsThisMonth = Trip::where('agent_id', $agent->id)
            ->whereDate('start_at', '>=', $thisMonth)
            ->count();
        
        // My customers
        $myCustomers = Customer::where('agent_id', $agent->id)->count();
        
        // My revenue this month
        $myRevenue = Trip::where('agent_id', $agent->id)
            ->where('status', 'completed')
            ->whereDate('completed_at', '>=', $thisMonth)
            ->sum('final_amount');
        
        // My commission this month (if commission_type is set)
        $commission = 0;
        if ($agent->commission_type === 'percentage') {
            $commission = ($myRevenue * $agent->commission_value) / 100;
        } elseif ($agent->commission_type === 'fixed') {
            $commission = $myBookingsThisMonth * $agent->commission_value;
        }
        
        // Credit status
        $availableCredit = $agent->credit_limit - $agent->credit_used;
        $creditPercentage = $agent->credit_limit > 0 
            ? ($agent->credit_used / $agent->credit_limit) * 100 
            : 0;

        return [
            Stat::make(__('My Bookings Today'), $myBookingsToday)
                ->description(sprintf('%d this week, %d this month', $myBookingsThisWeek, $myBookingsThisMonth))
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary')
                ->chart([3, 5, 4, 6, 8, 7, 10]),

            Stat::make(__('My Customers'), $myCustomers)
                ->description(__('Total managed customers'))
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),

            Stat::make(__('My Revenue'), 'SAR ' . number_format($myRevenue, 2))
                ->description(__('This month'))
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success')
                ->chart([5000, 6500, 7200, 8000, 8500, 9200, 10000]),

            Stat::make(__('My Commission'), 'SAR ' . number_format($commission, 2))
                ->description(sprintf('%s: %s', 
                    ucfirst($agent->commission_type ?? 'N/A'), 
                    $agent->commission_value ? number_format($agent->commission_value, 2) . ($agent->commission_type === 'percentage' ? '%' : '') : 'N/A'
                ))
                ->descriptionIcon('heroicon-o-credit-card')
                ->color('warning'),

            Stat::make(__('Available Credit'), 'SAR ' . number_format($availableCredit, 2))
                ->description(sprintf('%.1f%% used of SAR %s limit', 
                    $creditPercentage, 
                    number_format($agent->credit_limit, 2)
                ))
                ->descriptionIcon('heroicon-o-wallet')
                ->color($creditPercentage > 80 ? 'danger' : ($creditPercentage > 60 ? 'warning' : 'success')),
        ];
    }
}
