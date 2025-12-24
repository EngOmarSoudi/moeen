<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-filament::icon icon="heroicon-o-map" class="w-5 h-5" />
                Driver Location Map
            </div>
        </x-slot>

        <x-slot name="description">
            Real-time driver positions and your current location (Auto-refreshes every 30 seconds)
        </x-slot>

        <div class="space-y-4">
            <!-- Location Permission Alert -->
            <div id="location-permission-{{ $this->getId() }}" 
                 class="hidden p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <div class="flex items-start gap-3">
                    <x-filament::icon icon="heroicon-o-information-circle" class="w-5 h-5 text-blue-500 mt-0.5" />
                    <div class="flex-1">
                        <h4 class="font-semibold text-blue-900 dark:text-blue-100 text-sm">Location Access Requested</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                            Please allow location access to see your position on the map along with driver locations.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div id="driver-map-{{ $this->getId() }}" 
                 style="height: 600px; width: 100%; border-radius: 0.5rem; z-index: 0;" 
                 class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            </div>

            <!-- Legend -->
            <div class="flex flex-wrap gap-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-blue-500 shadow ring-2 ring-blue-200"></div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Your Location</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-green-500 shadow"></div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Available</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-yellow-500 shadow"></div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Busy</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-gray-500 shadow"></div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">On Break</span>
                </div>
                <div class="ml-auto text-sm text-gray-500 dark:text-gray-400">
                    {{ count($drivers) }} driver(s) active
                </div>
            </div>
        </div>

    </x-filament::section>

    @once
        @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
            crossorigin=""/>
        @endpush

        @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
        @endpush
    @endonce

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapId = 'driver-map-{{ $this->getId() }}';
            const permissionId = 'location-permission-{{ $this->getId() }}';
            const mapElement = document.getElementById(mapId);
            const permissionAlert = document.getElementById(permissionId);
            
            if (!mapElement) {
                console.error('Map container not found');
                return;
            }

            // Check if Leaflet is loaded
            if (typeof L === 'undefined') {
                console.error('Leaflet library not loaded');
                mapElement.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-red-500">Map library failed to load. Please refresh the page.</p></div>';
                return;
            }

            // Initialize map centered on Riyadh, Saudi Arabia
            const map = L.map(mapId).setView([24.7136, 46.6753], 12);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
            }).addTo(map);

            // Driver data from backend
            const drivers = @json($drivers);

            // Status color mapping
            const statusColors = {
                'available': '#22c55e',
                'busy': '#fbbf24',
                'on_break': '#9ca3af'
            };

            // Status display mapping
            const statusLabels = {
                'available': 'Available',
                'busy': 'Busy',
                'on_break': 'On Break'
            };

            // User location icon with pulsing animation
            function createUserIcon() {
                return L.divIcon({
                    className: 'user-location-marker',
                    html: `<div style="position: relative; width: 20px; height: 20px;">
                        <div style="position: absolute; width: 20px; height: 20px; background-color: #3b82f6; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3), 0 4px 12px rgba(0,0,0,0.4);"></div>
                        <div style="position: absolute; width: 20px; height: 20px; background-color: #3b82f6; border-radius: 50%; animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; opacity: 0.6;"></div>
                    </div>`,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10],
                    popupAnchor: [0, -10]
                });
            }

            // Custom icon function
            function createDriverIcon(status) {
                return L.divIcon({
                    className: 'custom-driver-marker',
                    html: `<div style="
                        background-color: ${statusColors[status] || '#6b7280'};
                        width: 36px;
                        height: 36px;
                        border-radius: 50%;
                        border: 3px solid white;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        cursor: pointer;
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" width="18" height="18">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    </div>`,
                    iconSize: [36, 36],
                    iconAnchor: [18, 18],
                    popupAnchor: [0, -18]
                });
            }

            let userMarker = null;
            let userCircle = null;
            const driverMarkers = [];

            // Get user's location
            function getUserLocation() {
                if ('geolocation' in navigator) {
                    permissionAlert.classList.remove('hidden');
                    
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            permissionAlert.classList.add('hidden');
                            
                            const userLat = position.coords.latitude;
                            const userLng = position.coords.longitude;
                            const accuracy = position.coords.accuracy;

                            if (userMarker) map.removeLayer(userMarker);
                            if (userCircle) map.removeLayer(userCircle);

                            userMarker = L.marker([userLat, userLng], {
                                icon: createUserIcon(),
                                zIndexOffset: 1000
                            }).addTo(map);

                            userCircle = L.circle([userLat, userLng], {
                                radius: accuracy,
                                color: '#3b82f6',
                                fillColor: '#3b82f6',
                                fillOpacity: 0.1,
                                weight: 1
                            }).addTo(map);

                            userMarker.bindPopup(`
                                <div style="min-width: 200px;">
                                    <div style="border-bottom: 2px solid #3b82f6; padding-bottom: 8px; margin-bottom: 10px;">
                                        <h3 style="font-weight: bold; font-size: 16px; color: #3b82f6;">📍 Your Location</h3>
                                    </div>
                                    <div style="font-size: 13px;">
                                        <p style="margin: 6px 0;"><strong>Latitude:</strong> ${userLat.toFixed(6)}</p>
                                        <p style="margin: 6px 0;"><strong>Longitude:</strong> ${userLng.toFixed(6)}</p>
                                        <p style="margin: 6px 0;"><strong>Accuracy:</strong> ±${Math.round(accuracy)}m</p>
                                    </div>
                                </div>
                            `);

                            const bounds = L.latLngBounds([[userLat, userLng]]);
                            drivers.forEach(d => bounds.extend([d.latitude, d.longitude]));
                            
                            if (drivers.length > 0) {
                                map.fitBounds(bounds.pad(0.15));
                            } else {
                                map.setView([userLat, userLng], 14);
                            }
                        },
                        function(error) {
                            permissionAlert.classList.add('hidden');
                            console.warn('Geolocation error:', error);
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                }
            }

            let userMarker = null;
            let userCircle = null;
            const driverMarkers = [];

            // Add driver markers
            if (drivers.length === 0) {
                // Show message if no drivers found
                L.popup()
                    .setLatLng([24.7136, 46.6753])
                    .setContent(`
                        <div style="text-align: center; padding: 20px; min-width: 250px;">
                            <div style="font-size: 48px; margin-bottom: 10px;">🚗</div>
                            <h3 style="font-weight: bold; margin-bottom: 8px; font-size: 16px;">No Active Drivers</h3>
                            <p style="color: #6b7280; font-size: 14px;">Driver locations will appear here when drivers are active and their GPS is enabled.</p>
                            <p style="color: #9ca3af; font-size: 12px; margin-top: 10px;">Tip: Run the seeder to add sample driver locations</p>
                        </div>
                    `)
                    .openOn(map);
            } else {
                drivers.forEach(driver => {
                    const marker = L.marker([driver.latitude, driver.longitude], {
                        icon: createDriverIcon(driver.status)
                    }).addTo(map);

                    // Create popup content
                    const popupContent = `
                        <div style="min-width: 220px; font-family: system-ui, -apple-system, sans-serif;">
                            <div style="border-bottom: 2px solid ${statusColors[driver.status]}; padding-bottom: 8px; margin-bottom: 10px;">
                                <h3 style="font-weight: bold; font-size: 16px; margin: 0;">${driver.name}</h3>
                            </div>
                            <div style="font-size: 13px; color: #374151; line-height: 1.6;">
                                <p style="margin: 6px 0; display: flex; align-items: center;">
                                    <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    <strong>Phone:</strong> ${driver.phone}
                                </p>
                                <p style="margin: 6px 0;">
                                    <strong>Status:</strong> 
                                    <span style="
                                        display: inline-block;
                                        padding: 3px 10px;
                                        border-radius: 6px;
                                        background-color: ${statusColors[driver.status]};
                                        color: white;
                                        font-size: 11px;
                                        font-weight: 600;
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                    ">${statusLabels[driver.status]}</span>
                                </p>
                                ${driver.speed ? `<p style="margin: 6px 0;"><strong>Speed:</strong> ${driver.speed} km/h</p>` : ''}
                                ${driver.address ? `
                                    <p style="margin: 6px 0; padding-top: 6px; border-top: 1px solid #e5e7eb;">
                                        <svg style="width: 16px; height: 16px; display: inline; margin-right: 4px;" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        ${driver.address}
                                    </p>
                                ` : ''}
                                <p style="margin: 8px 0 0; padding-top: 6px; border-top: 1px solid #e5e7eb; font-style: italic; color: #9ca3af; font-size: 11px;">
                                    📍 Updated ${driver.recorded_at}
                                </p>
                            </div>
                        </div>
                    `;

                    marker.bindPopup(popupContent, {
                        maxWidth: 300,
                        className: 'driver-popup'
                    });
                    driverMarkers.push(marker);
                });

                // Fit map to show all markers
                const group = L.featureGroup(driverMarkers);
                map.fitBounds(group.getBounds().pad(0.15));
            }

            // Request user location immediately
            getUserLocation();

            // Fix map rendering issue
            setTimeout(() => {
                map.invalidateSize();
            }, 250);

            // Auto-refresh every 30 seconds using Livewire
            setInterval(() => {
                if (window.Livewire) {
                    window.Livewire.find('{{ $this->getId() }}')?.call('$refresh');
                }
            }, 30000);

            console.log('Driver location map initialized with ' + drivers.length + ' drivers');
        });
    </script>

    <style>
        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(2.5); opacity: 0; }
        }
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .leaflet-popup-tip {
            box-shadow: 0 3px 14px rgba(0,0,0,0.15);
        }
    </style>
</x-filament-widgets::widget>
