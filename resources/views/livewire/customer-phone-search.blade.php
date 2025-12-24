<div class="space-y-4">
    {{-- Phone Number Search Input --}}
    <div class="rounded-lg border border-gray-300 p-4 bg-white">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Customer Phone Number
        </label>
        <input 
            type="tel"
            wire:model.live="phoneNumber"
            placeholder="Enter phone number to search for customer"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
    </div>

    {{-- Customer Found Message --}}
    @if($searchPerformed && !empty($customerDetails))
        <div class="rounded-lg border border-green-300 bg-green-50 p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-green-800">Customer Found!</h3>
                    
                    {{-- Customer Details Card --}}
                    <div class="mt-3 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-700 font-medium">Name:</span>
                            <span class="text-gray-900">{{ $customerDetails['name'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700 font-medium">Phone:</span>
                            <span class="text-gray-900">{{ $customerDetails['phone'] }}</span>
                        </div>
                        @if($customerDetails['email'])
                        <div class="flex justify-between">
                            <span class="text-gray-700 font-medium">Email:</span>
                            <span class="text-gray-900">{{ $customerDetails['email'] }}</span>
                        </div>
                        @endif
                        @if($customerDetails['nationality'])
                        <div class="flex justify-between">
                            <span class="text-gray-700 font-medium">Nationality:</span>
                            <span class="text-gray-900">{{ $customerDetails['nationality'] }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Select Button --}}
                    <button
                        type="button"
                        wire:click="selectCustomer({{ $customerDetails['id'] }})"
                        class="mt-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                        <svg class="h-4 w-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Select This Customer
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- No Customer Found - Show Create Form --}}
    @if($searchPerformed && empty($customerDetails) && !empty($phoneNumber))
        <div class="rounded-lg border border-blue-300 bg-blue-50 p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-blue-800">No Customer Found</h3>
                    <p class="mt-1 text-sm text-blue-700">
                        No customer exists with phone number <strong>{{ $phoneNumber }}</strong>. Click the "+" button to create a new customer profile with this phone number.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
