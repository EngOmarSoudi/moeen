<x-filament-widgets::widget>
    @php
        $widgetId = $this->getId();
        $mapId = 'trip-route-map-' . $widgetId;
    @endphp

    <div class="w-full">
        <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <!-- Map Container -->
            <div 
                id="{{ $mapId }}" 
                class="h-[500px] w-full"
                style="z-index: 0;"
                wire:ignore
            ></div>

            <!-- Floating Info Card -->
            <div class="pointer-events-none absolute left-4 top-4 z-[1000] flex flex-col gap-2">
                <div class="pointer-events-auto rounded-lg border border-white/20 bg-white/70 p-3 shadow-xl backdrop-blur-md dark:bg-gray-800/70">
                    <h3 class="flex items-center gap-2 text-sm font-bold text-gray-900 dark:text-white">
                        <span class="flex h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                        Active Route Visualization
                    </h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ count($trips) }} active trip(s) currently being monitored
                    </p>
                </div>
            </div>

            <!-- Legend -->
            <div class="pointer-events-none absolute bottom-4 right-4 z-[1000]">
                <div class="pointer-events-auto flex items-center gap-4 rounded-full border border-white/20 bg-white/70 px-4 py-2 shadow-lg backdrop-blur-md dark:bg-gray-800/70">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                        <span class="text-[10px] font-medium text-gray-600 dark:text-gray-300">Pickup</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-red-500"></span>
                        <span class="text-[10px] font-medium text-gray-600 dark:text-gray-300">Drop-off</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                        <span class="text-[10px] font-medium text-gray-600 dark:text-gray-300">Driver</span>
                    </div>
                </div>
            </div>
        </div>

        @push('styles')
        <style>
            #{{ $mapId }} {
                height: 500px !important;
                width: 100% !important;
                z-index: 1 !important;
            }
            .leaflet-container {
                height: 100% !important;
                width: 100% !important;
            }
            .animated-polyline-widget {
                stroke-dasharray: 10, 15;
                animation: dash-movement 60s linear infinite;
                filter: drop-shadow(0 0 12px rgba(59, 130, 246, 0.5));
            }
            @keyframes dash-movement {
                to { stroke-dashoffset: -2000; }
            }
            .leaflet-popup-content-wrapper {
                border-radius: 12px;
                padding: 0;
                overflow: hidden;
            }
            .leaflet-popup-content {
                margin: 0;
            }
        </style>
        @endpush
    </div>

    @push('scripts')
    <script>
        (function() {
            function initTripRouteMap() {
                const mapId = '{{ $mapId }}';
                const trips = @json($trips);
                let map = null;
                let markers = [];
                let polylines = [];

                function initMap() {
                    const mapEl = document.getElementById(mapId);
                    
                    if (!mapEl) {
                        console.error('Map element not found:', mapId);
                        return;
                    }

                    // Check if Leaflet is loaded
                    if (typeof L === 'undefined') {
                        console.error('Leaflet not loaded, retrying...');
                        setTimeout(initMap, 500);
                        return;
                    }

                    // Prevent re-initialization
                    if (mapEl._leaflet_id) {
                        console.log('Map already initialized');
                        return;
                    }

                    console.log('Initializing trip route map...');

                    map = L.map(mapId, {
                        zoomControl: true,
                        scrollWheelZoom: true,
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors',
                        maxZoom: 19,
                    }).addTo(map);

                    renderTrips();
                    
                    // Multiple invalidateSize calls with increasing delays
                    setTimeout(() => map.invalidateSize(true), 100);
                    setTimeout(() => map.invalidateSize(true), 500);
                    setTimeout(() => map.invalidateSize(true), 1000);
                }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function renderTrips() {
                if (!trips || trips.length === 0) {
                    map.setView([24.7136, 46.6753], 6);
                    return;
                }

                const bounds = L.latLngBounds();

                trips.forEach(trip => {
                    if (trip.origin_lat && trip.origin_lng) {
                        const origin = [trip.origin_lat, trip.origin_lng];
                        const dest = [trip.destination_lat, trip.destination_lng];
                        
                        bounds.extend(origin);
                        if (trip.destination_lat) bounds.extend(dest);

                        if (trip.destination_lat) {
                            const polyline = L.polyline([origin, dest], {
                                color: '#3b82f6',
                                weight: 4,
                                opacity: 0.8,
                                className: 'animated-polyline-widget'
                            }).addTo(map);
                            polylines.push(polyline);
                        }

                        const originIcon = L.divIcon({
                            className: 'custom-premium-marker',
                            html: '<div class="relative flex items-center justify-center"><div class="absolute w-8 h-8 rounded-full animate-ping opacity-20" style="background-color: #22c55e"></div><div class="relative w-4 h-4 rounded-full border-2 border-white dark:border-gray-900 shadow-lg" style="background-color: #22c55e"></div></div>',
                            iconSize: [32, 32],
                            iconAnchor: [16, 16]
                        });

                        const popupContent = `
                            <div class="p-2 min-w-[150px]">
                                <div class="text-xs font-bold text-gray-500 uppercase mb-1">${trip.service_kind || 'Trip'}</div>
                                <div class="font-bold text-sm mb-1">${trip.code}</div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-primary-100 text-primary-700 font-medium">${trip.status}</span>
                                </div>
                                <div class="text-xs border-t pt-2 mt-2">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-500">From:</span>
                                        <span class="font-medium truncate">${escapeHtml(trip.origin_name || 'Pickup')}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">To:</span>
                                        <span class="font-medium truncate">${escapeHtml(trip.destination_name || 'Drop-off')}</span>
                                    </div>
                                </div>
                            </div>
                        `;

                        const marker = L.marker(origin, { icon: originIcon })
                            .bindPopup(popupContent)
                            .addTo(map);
                        markers.push(marker);

                        if (trip.destination_lat) {
                            const destIcon = L.divIcon({
                                className: 'custom-premium-marker',
                                html: '<div class="relative flex items-center justify-center"><div class="absolute w-8 h-8 rounded-full animate-ping opacity-20" style="background-color: #ef4444"></div><div class="relative w-4 h-4 rounded-full border-2 border-white dark:border-gray-900 shadow-lg" style="background-color: #ef4444"></div></div>',
                                iconSize: [32, 32],
                                iconAnchor: [16, 16]
                            });
                            L.marker(dest, { icon: destIcon }).addTo(map);
                        }

                        if (trip.current_lat && trip.current_lng) {
                            const driverIcon = L.divIcon({
                                className: 'custom-premium-marker',
                                html: '<div class="relative flex items-center justify-center"><div class="absolute w-10 h-10 rounded-full animate-ping opacity-30" style="background-color: #f59e0b"></div><div class="relative w-8 h-8 rounded-full bg-white shadow-xl flex items-center justify-center border-2 border-amber-500"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-amber-600"><path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 116 0h3a.75.75 0 00.75-.75V15z" /><path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .414.336.75.75.75h2.25c.414 0 .75-.336.75-.75V7.5c0-.414-.336-.75-.75-.75h-2.25z" /></svg></div></div>',
                                iconSize: [40, 40],
                                iconAnchor: [20, 20],
                                popupAnchor: [0, -20]
                            });
                            
                            L.marker([trip.current_lat, trip.current_lng], { icon: driverIcon })
                                .bindPopup('<div class="font-bold text-center">🚖 Driver Location<br><span class="text-xs text-gray-500">Live Tracking</span></div>')
                                .addTo(map);
                            
                            bounds.extend([trip.current_lat, trip.current_lng]);
                        }
                    }
                });

                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }
            }

                // Initialize when DOM is ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initMap);
                } else {
                    setTimeout(initMap, 100);
                }
            }

            // Initialize when ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTripRouteMap);
            } else {
                setTimeout(initTripRouteMap, 100);
            }
        })();
    </script>
    @endpush
</x-filament-widgets::widget>
