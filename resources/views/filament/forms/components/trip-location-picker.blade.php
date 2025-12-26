<div 
    wire:ignore 
    x-data="tripLocationPicker()" 
    x-init="initPicker('{{ $getLocationType() }}', '{{ $getLatField() }}', '{{ $getLngField() }}', '{{ $getStatePath() }}')"
    class="space-y-3"
>
    <div>
        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 text-sm font-medium leading-6 text-gray-950 dark:text-white">
            {{ $getLocationType() === 'origin' ? __('Pickup Location') : __('Drop-off Location') }}
            <span class="text-danger-600">*</span>
        </label>
        <input
            type="text"
            x-ref="nameInput"
            name="data[{{ $getStatePath() }}]"
            placeholder="{{ __('Click on the map to select a location') }}"
            class="fi-input block w-full py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-2 focus:ring-primary-500 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6 bg-white dark:bg-gray-900 ps-3 pe-3 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm"
        >
    </div>

    <div>
        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 text-sm font-medium leading-6 text-gray-950 dark:text-white mb-2">
            {{ __('Map View') }} 
            <span class="text-xs text-gray-500">({{ __('Click to select location') }})</span>
        </label>
        <div
            x-ref="mapContainer"
            class="h-72 rounded-lg border border-gray-300 dark:border-gray-700 overflow-hidden shadow-sm"
            style="background: linear-gradient(135deg, #f0f0f0 25%, #e8e8e8 25%, #e8e8e8 50%, #f0f0f0 50%, #f0f0f0 75%, #e8e8e8 75%); background-size: 20px 20px; min-height: 288px; z-index: 0;"
        ></div>
    </div>

    <div class="flex gap-6 text-sm bg-gray-50 dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-2">
            <span class="text-gray-500 dark:text-gray-400">Lat:</span>
            <span x-ref="latDisplay" class="font-mono font-medium text-gray-900 dark:text-white">{{ __('Not set') }}</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-gray-500 dark:text-gray-400">Lng:</span>
            <span x-ref="lngDisplay" class="font-mono font-medium text-gray-900 dark:text-white">{{ __('Not set') }}</span>
        </div>
    </div>
</div>
