<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            {{ __('Live Driver Locations') }}
        </x-slot>

        <x-slot name="headerEnd">
            <x-filament::button
                size="sm"
                icon="heroicon-o-arrow-path"
                wire:click="$refresh"
            >
                {{ __('Refresh') }}
            </x-filament::button>
        </x-slot>

        <div class="space-y-4">
            {{-- Map Container --}}
            <div 
                id="live-drivers-map" 
                class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700"
                style="height: 500px; min-height: 400px;"
                wire:ignore
            ></div>

            {{-- Legend --}}
            <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Available') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Busy') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Offline') }}</span>
                </div>
            </div>
        </div>
   </x-filament::section>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #live-drivers-map {
            height: 500px !important;
            min-height: 400px !important;
            width: 100% !important;
            z-index: 1;
        }
        .leaflet-container {
            height: 100% !important;
            width: 100% !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        (function() {
            // Wait for everything to load
            function initDriverMap() {
                const DEFAULT_CENTER = [24.7136, 46.6753];
                const DEFAULT_ZOOM = 11;
                const API_ENDPOINT = '/api/drivers/geo';
                const REFRESH_INTERVAL = 15000;

                const mapContainer = document.getElementById('live-drivers-map');
                if (!mapContainer) {
                    console.error('Map container not found');
                    return;
                }

                // Check if Leaflet is loaded
                if (typeof L === 'undefined') {
                    console.error('Leaflet not loaded');
                    setTimeout(initDriverMap, 500);
                    return;
                }

                // Prevent double initialization
                if (mapContainer._leaflet_id) {
                    console.log('Map already initialized');
                    return;
                }

                console.log('Initializing driver map...');

                // Create map
                const map = L.map('live-drivers-map', {
                    zoomSnap: 0.5,
                    minZoom: 4,
                    maxZoom: 19
                }).setView(DEFAULT_CENTER, DEFAULT_ZOOM);

                // Add tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                // Add scale
                L.control.scale({ imperial: false }).addTo(map);

                // Fix map size - multiple attempts with increasing delays
                setTimeout(() => map.invalidateSize(true), 100);
                setTimeout(() => map.invalidateSize(true), 500);
                setTimeout(() => map.invalidateSize(true), 1000);
                window.addEventListener('resize', () => map.invalidateSize(true));

            // Store markers
            let markers = new Map();

            // Status colors
            const statusColors = {
                'available': '#22c55e',
                'idle': '#22c55e',
                'busy': '#ef4444',
                'offline': '#9ca3af',
                'suspended': '#dc2626'
            };

            function getStatusColor(status) {
                const normalized = String(status || 'offline').toLowerCase();
                return statusColors[normalized] || '#9ca3af';
            }

            function translateStatus(status) {
                const normalized = String(status || 'offline').toLowerCase();
                const translations = {
                    'available': '{{ __("Available") }}',
                    'idle': '{{ __("Available") }}',
                    'busy': '{{ __("Busy") }}',
                    'offline': '{{ __("Offline") }}',
                    'suspended': '{{ __("Suspended") }}'
                };
                return translations[normalized] || '{{ __("Offline") }}';
            }

            function createPopupContent(driver) {
                const statusClass = String(driver.status || 'offline').toLowerCase();
                const batteryClass = (driver.battery && driver.battery < 15) ? 'text-red-600 font-semibold' : '';
                
                let html = `
                    <div class="p-2 min-w-[200px]">
                        <div class="font-bold text-lg mb-2">${driver.name || 'Driver'}</div>
                        <div class="space-y-1 text-sm">
                            <div><strong>{{ __("Status") }}:</strong> <span class="font-medium">${translateStatus(driver.status)}</span></div>
                `;
                
                if (driver.phone) {
                    html += `<div><strong>{{ __("Phone") }}:</strong> ${driver.phone}</div>`;
                }
                
                if (driver.battery !== null && driver.battery !== undefined) {
                    html += `<div class="${batteryClass}"><strong>{{ __("Battery") }}:</strong> ${driver.battery}%</div>`;
                }
                
                if (driver.rating) {
                    html += `<div><strong>{{ __("Rating") }}:</strong> ${driver.rating} ⭐</div>`;
                }
                
                html += `</div></div>`;
                return html;
            }

            function drawOrUpdateMarker(driver) {
                const { id, lat, lng, status } = driver;
                
                if (!lat || !lng) return;

                const color = getStatusColor(status);
                const key = `driver_${id}`;

                if (markers.has(key)) {
                    const marker = markers.get(key);
                    marker.setLatLng([lat, lng]);
                    marker.setStyle({
                        color: color,
                        fillColor: color
                    });
                    marker.setPopupContent(createPopupContent(driver));
                } else {
                    const marker = L.circleMarker([lat, lng], {
                        radius: 10,
                        color: '#fff',
                        weight: 3,
                        fillColor: color,
                        fillOpacity: 0.9
                    }).addTo(map);

                    marker.bindPopup(createPopupContent(driver));
                    markers.set(key, marker);
                }
            }

            function removeStaleMarkers(currentDriverIds) {
                const currentKeys = currentDriverIds.map(id => `driver_${id}`);
                
                markers.forEach((marker, key) => {
                    if (!currentKeys.includes(key)) {
                        map.removeLayer(marker);
                        markers.delete(key);
                    }
                });
            }

            async function loadDrivers() {
                try {
                    const response = await fetch(API_ENDPOINT);
                    const json = await response.json();
                    
                    const driversData = json.data || [];
                    
                    console.log(`Loaded ${driversData.length} drivers`);
                    
                    driversData.forEach(drawOrUpdateMarker);
                    
                    const currentIds = driversData.map(d => d.id);
                    removeStaleMarkers(currentIds);
                    
                    if (!loadDrivers._fitted && driversData.length > 0) {
                        fitMapToBounds();
                        loadDrivers._fitted = true;
                    }
                    
                } catch (error) {
                    console.error('Error loading drivers:', error);
                }
            }

            function fitMapToBounds() {
                const latlngs = [];
                markers.forEach(marker => {
                    latlngs.push(marker.getLatLng());
                });
                
                if (latlngs.length > 0) {
                    const bounds = L.latLngBounds(latlngs);
                    map.fitBounds(bounds, {
                        padding: [30, 30],
                        maxZoom: DEFAULT_ZOOM
                    });
                }
            }

                // Initial load
                loadDrivers();
                
                // Auto-refresh
                setInterval(loadDrivers, REFRESH_INTERVAL);
                
                // Livewire refresh hook
                if (typeof Livewire !== 'undefined') {
                    Livewire.on('refreshMap', () => {
                        loadDrivers();
                    });
                }
            }

            // Initialize when ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initDriverMap);
            } else {
                // DOM already loaded
                setTimeout(initDriverMap, 100);
            }
        })();
    </script>
    @endpush
</x-filament-widgets::widget>
