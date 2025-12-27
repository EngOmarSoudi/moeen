<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

/**
 * Live Driver Map Widget
 * Shows real-time driver locations on a map
 * Visible to: Admin, Agent
 */
class LiveDriverMapWidget extends Widget
{
    protected static ?int $sort = 10;
    
    // Non-static property - must match parent class
    protected string $view = 'filament.widgets.live-driver-map-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Live Driver Locations';
    
    protected static ?string $maxHeight = '600px';

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('admin') || $user->hasRole('agent'));
    }

    protected function getViewData(): array
    {
        return [
            'heading' => static::$heading,
        ];
    }
}
