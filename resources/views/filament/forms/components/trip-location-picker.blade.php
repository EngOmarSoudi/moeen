<div
    x-data="{
        locationName: $wire.entangle('{{ $getStatePath() }}'),
        lat: $wire.entangle('{{ $getLatField() }}'),
        lng: $wire.entangle('{{ $getLngField() }}'),
        savedPlaces: @js($getSavedPlaces()),
        searchQuery: '',
        searchResults: [],
        isSearching: false,
        showDropdown: false,
        map: null,
        marker: null,
        locationType: '{{ $getLocationType() }}',

        init() {
            this.$nextTick(() => {
                this.initMap();
            });
        },

        initMap() {
            const mapId = 'location-map-{{ $getLocationType() }}-{{ $getId() }}';
            const mapEl = document.getElementById(mapId);
            if (!mapEl || typeof L === 'undefined') {
                console.warn('Map element or Leaflet not found, retrying...');
                setTimeout(() => this.initMap(), 500);
                return;
            }

            // Default center: Riyadh, Saudi Arabia
            const defaultCenter = [24.7136, 46.6753];
            const initialCenter = this.lat && this.lng ? [parseFloat(this.lat), parseFloat(this.lng)] : defaultCenter;
            const initialZoom = this.lat && this.lng ? 15 : 12;

            this.map = L.map(mapId).setView(initialCenter, initialZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(this.map);

            // Add initial marker if coordinates exist
            if (this.lat && this.lng) {
                this.addMarker([parseFloat(this.lat), parseFloat(this.lng)]);
            }

            // Click to select location
            this.map.on('click', (e) => {
                this.setLocation(e.latlng.lat, e.latlng.lng);
                this.reverseGeocode(e.latlng.lat, e.latlng.lng);
            });

            // Fix map display issues
            setTimeout(() => this.map.invalidateSize(), 150);
        },

        addMarker(coords) {
            if (this.marker) {
                this.map.removeLayer(this.marker);
            }

            // Premium icon based on location type
            const color = this.locationType === 'origin' ? '#22c55e' : '#ef4444';
            const glowColor = this.locationType === 'origin' ? 'rgba(34, 197, 94, 0.4)' : 'rgba(239, 68, 68, 0.4)';
            const icon = L.divIcon({
                className: 'custom-premium-marker',
                html: `
                    <div class='relative flex items-center justify-center'>
                        <div class='absolute w-10 h-10 rounded-full animate-ping opacity-20' style='background-color: ${color}'></div>
                        <div class='relative w-5 h-5 rounded-full border-4 border-white dark:border-gray-900 shadow-xl' 
                             style='background-color: ${color}; box-shadow: 0 0 20px ${glowColor}, inset 0 0 10px rgba(0,0,0,0.2);'>
                        </div>
                    </div>
                `,
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });

            this.marker = L.marker(coords, {
                draggable: true,
                icon: icon
            }).addTo(this.map);

            this.marker.on('dragend', (e) => {
                const pos = e.target.getLatLng();
                this.setLocation(pos.lat, pos.lng);
                this.reverseGeocode(pos.lat, pos.lng);
            });
        },

        setLocation(lat, lng) {
            this.lat = parseFloat(lat).toFixed(8);
            this.lng = parseFloat(lng).toFixed(8);
            this.addMarker([lat, lng]);
        },

        async searchPlaces() {
            if (this.searchQuery.length < 3) {
                this.searchResults = [];
                return;
            }

            this.isSearching = true;
            this.showDropdown = true;

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}&countrycodes=sa&limit=5`,
                    { headers: { 'Accept-Language': 'en' } }
                );
                this.searchResults = await response.json();
            } catch (error) {
                console.error('Search error:', error);
                this.searchResults = [];
            }

            this.isSearching = false;
        },

        selectSearchResult(result) {
            this.locationName = result.display_name.split(',')[0];
            this.setLocation(result.lat, result.lon);
            this.map.setView([result.lat, result.lon], 15);
            this.searchQuery = '';
            this.searchResults = [];
            this.showDropdown = false;
        },

        selectSavedPlace(place) {
            this.locationName = place.name;
            this.setLocation(place.lat, place.lng);
            this.map.setView([place.lat, place.lng], 15);
            this.showDropdown = false;
        },

        async reverseGeocode(lat, lng) {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`,
                    { headers: { 'Accept-Language': 'en' } }
                );
                const data = await response.json();
                if (data.display_name) {
                    this.locationName = data.display_name.split(',').slice(0, 2).join(', ');
                }
            } catch (error) {
                console.error('Reverse geocode error:', error);
            }
        }
    }"
    class="space-y-3"
>
    <!-- Location Name Input -->
    <div>
        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 text-sm font-medium leading-6 text-gray-950 dark:text-white">
            {{ $getLocationType() === 'origin' ? 'Pickup Location' : 'Drop-off Location' }}
        </label>
        <input
            type="text"
            x-model="locationName"
            placeholder="Enter location name or click on map"
            class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-white/0 ps-3 pe-3 rounded-lg border border-gray-300 dark:border-gray-700"
        >
    </div>

    <!-- Search & Saved Places -->
    <div class="grid grid-cols-2 gap-3">
        <!-- Place Search -->
        <div class="relative">
            <input
                type="text"
                x-model="searchQuery"
                @input.debounce.300ms="searchPlaces()"
                @focus="showDropdown = true"
                placeholder="Search for a place..."
                class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-white/0 ps-3 pe-3 rounded-lg border border-gray-300 dark:border-gray-700"
            >
            
            <!-- Search Results Dropdown -->
            <div
                x-show="showDropdown && (searchResults.length > 0 || isSearching)"
                x-transition
                @click.outside="showDropdown = false"
                class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 max-h-60 overflow-y-auto"
            >
                <template x-if="isSearching">
                    <div class="p-3 text-center text-gray-500">
                        <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </template>
                <template x-for="result in searchResults" :key="result.place_id">
                    <button
                        type="button"
                        @click="selectSearchResult(result)"
                        class="w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 last:border-0"
                    >
                        <div class="font-medium text-sm text-gray-900 dark:text-white" x-text="result.display_name.split(',')[0]"></div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="result.display_name"></div>
                    </button>
                </template>
            </div>
        </div>

        <!-- Saved Places Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button
                type="button"
                @click="open = !open"
                class="w-full flex items-center justify-between px-3 py-2 text-sm bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700"
            >
                <span class="text-gray-700 dark:text-gray-300">Select from saved places</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div
                x-show="open"
                x-transition
                @click.outside="open = false"
                class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 max-h-60 overflow-y-auto"
            >
                <template x-if="savedPlaces.length === 0">
                    <div class="p-3 text-center text-gray-500 text-sm">No saved places available</div>
                </template>
                <template x-for="place in savedPlaces" :key="place.id">
                    <button
                        type="button"
                        @click="selectSavedPlace(place); open = false"
                        class="w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 last:border-0"
                    >
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <div>
                                <div class="font-medium text-sm text-gray-900 dark:text-white" x-text="place.name"></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400" x-text="place.address || place.type"></div>
                            </div>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div
        id="location-map-{{ $getLocationType() }}-{{ $getId() }}"
        class="h-64 rounded-lg border border-gray-300 dark:border-gray-700"
        style="z-index: 0; min-height: 256px;"
        wire:ignore
    ></div>

    <!-- Coordinates Display -->
    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
        <div class="flex items-center gap-1">
            <span>Lat:</span>
            <span x-text="lat || 'Not set'" class="font-mono"></span>
        </div>
        <div class="flex items-center gap-1">
            <span>Lng:</span>
            <span x-text="lng || 'Not set'" class="font-mono"></span>
        </div>
    </div>

    <!-- Hidden inputs for form submission -->
    <input type="hidden" name="{{ $getLatField() }}" :value="lat">
    <input type="hidden" name="{{ $getLngField() }}" :value="lng">

    <style>
        .custom-premium-marker {
            background: transparent !important;
            border: none !important;
        }
    </style>
</div>
