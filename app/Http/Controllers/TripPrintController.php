<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripPrintController extends Controller
{
    public function __invoke(Trip $trip)
    {
        $trip->load(['customer', 'travelRoute', 'agent', 'routeTemplate', 'latestTrackingPoint']);
        
        return view('trips.print', compact('trip'));
    }
}
