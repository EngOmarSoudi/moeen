<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripTrackingPoint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TripTrackingController extends Controller
{
    /**
     * Store a new tracking point for a trip
     * 
     * @param Request $request
     * @param Trip $trip
     * @return JsonResponse
     */
    public function store(Request $request, Trip $trip): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'speed' => 'nullable|numeric|min:0',
            'heading' => 'nullable|numeric|between:0,360',
            'accuracy' => 'nullable|numeric|min:0',
            'recorded_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Only allow tracking for in-progress trips
        if ($trip->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Tracking is only allowed for trips in progress',
            ], 400);
        }

        $trackingPoint = $trip->trackingPoints()->create([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'speed' => $request->speed,
            'heading' => $request->heading,
            'accuracy' => $request->accuracy,
            'recorded_at' => $request->recorded_at ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $trackingPoint,
        ], 201);
    }

    /**
     * Get all tracking points for a trip
     * 
     * @param Trip $trip
     * @return JsonResponse
     */
    public function index(Trip $trip): JsonResponse
    {
        $points = $trip->trackingPoints()
            ->orderBy('recorded_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $points,
            'meta' => [
                'total_points' => $points->count(),
                'route_deviation_percentage' => $trip->getRouteDeviationPercentage(),
                'route_followed' => $trip->wasRouteFollowed(),
            ],
        ]);
    }

    /**
     * Get route analysis for a trip
     * 
     * @param Trip $trip
     * @return JsonResponse
     */
    public function analysis(Trip $trip): JsonResponse
    {
        $points = $trip->trackingPoints;

        return response()->json([
            'success' => true,
            'analysis' => [
                'total_tracking_points' => $points->count(),
                'route_deviation_percentage' => round($trip->getRouteDeviationPercentage(), 2),
                'route_followed' => $trip->wasRouteFollowed(),
                'planned_route' => [
                    'origin' => [
                        'lat' => $trip->origin_lat,
                        'lng' => $trip->origin_lng,
                        'name' => $trip->origin,
                    ],
                    'destination' => [
                        'lat' => $trip->destination_lat,
                        'lng' => $trip->destination_lng,
                        'name' => $trip->destination,
                    ],
                ],
                'actual_path' => $points->map(fn ($p) => [
                    'lat' => $p->latitude,
                    'lng' => $p->longitude,
                    'recorded_at' => $p->recorded_at->toIso8601String(),
                ]),
            ],
        ]);
    }
}
