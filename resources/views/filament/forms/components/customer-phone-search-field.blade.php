@php
    $id = $getId();
    $isDisabled = $isDisabled();
@endphp

<x-dynamic-component
    :component="resolve_static(\Filament\Forms\Components\Field::class, 'getDefaultView', [])"
    :field="$field"
>
    <div x-data="{ state: @js($getState()) }">
        {{-- Phone Search Input --}}
        <div class="mb-4">
            <x-filament::input.wrapper>
                <x-filament::input.label for="{{ $id }}">
                    {{ $getLabel() }}
                </x-filament::input.label>
                <input
                    type="tel"
                    id="{{ $id }}"
                    placeholder="Enter phone number to search customer"
                    @change="$wire.searchCustomerByPhone($event.target.value)"
                    @input="$wire.searchCustomerByPhone($event.target.value)"
                    class="border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed block w-full text-sm px-3 py-2 transition"
                    :disabled="$wire.disabled"
                />
            </x-filament::input.wrapper>
        </div>

        {{-- Customer Selection Field (Hidden) --}}
        <input
            type="hidden"
            name="{{ $getName() }}"
            x-model="state"
            @change="$wire.setFieldState('{{ $getName() }}', state)"
        />

        {{-- Results Container --}}
        <div x-show="$wire.searchResults" class="mt-4 space-y-3">
            {{-- Customer Found --}}
            <div x-show="$wire.customerFound && !$wire.createFormVisible" class="rounded-lg border border-green-300 bg-green-50 p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-green-800">Customer Found!</h3>
                        <div class="mt-3 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="font-medium">Name:</span>
                                <span x-text="$wire.foundCustomer?.name"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Phone:</span>
                                <span x-text="$wire.foundCustomer?.phone"></span>
                            </div>
                            <div class="flex justify-between" x-show="$wire.foundCustomer?.email">
                                <span class="font-medium">Email:</span>
                                <span x-text="$wire.foundCustomer?.email"></span>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="state = $wire.foundCustomer.id; $wire.selectCustomer($wire.foundCustomer.id)"
                            class="mt-3 inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
                        >
                            Select This Customer
                        </button>
                    </div>
                </div>
            </div>

            {{-- No Customer Found --}}
            <div x-show="!$wire.customerFound && $wire.searchResults && !$wire.createFormVisible" class="rounded-lg border border-blue-300 bg-blue-50 p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-blue-800">No Customer Found</h3>
                        <p class="mt-1 text-sm text-blue-700">
                            Click the "+" button below to create a new customer profile.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($errors->has($getName()))
            <p class="mt-2 text-sm text-red-600">
                {{ $errors->first($getName()) }}
            </p>
        @endif
    </div>
</x-dynamic-component>
