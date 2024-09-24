<div>
    <div class="mt-20 max-w-screen-xl mx-auto p-4">

        <div class="flex items-center justify-between mb-4">
            <h4 class="text-2xl font-bold leading-none text-gray-900 dark:text-white">{{ __('Invoices') }}</h4>
            {{-- <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                View all
            </a> --}}
        </div>

        <div class="max-w-full mx-auto">
            @forelse ($invoices as $invoice)
                <a href="{{ route('invoice.detail', $invoice['docEntry']) }}"
                    class="border border-gray-300 block bg-white rounded-lg shadow overflow-hidden mb-2 hover:bg-gray-100 focus:bg-gray-200 active:bg-gray-300 transition">
                    <div class="flex items-center justify-between px-4 py-2">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $invoice['cardName'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $invoice['shipTo'] }}</p>
                            <p class="text-sm text-gray-500">No.{{ $invoice['docNum'] }}</p>
                        </div>
                        @if ($invoice['signatureStatus'] === 'NEW')
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ __("Not Signed") }}</span>
                        @else
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ __("Signed") }}</span>
                        @endif
                    </div>
                </a>
            @empty
            <div class="mt-10 flex flex-col items-center justify-center">
                <img class="h-auto max-w-xs w-3/4 sm:max-w-xs" src="{{ asset('empty.svg') }}" alt="empty results">
                <span class="mt-2 text-gray-600">{{ __("No results") }}</span>
            </div>
            
            @endforelse

        </div>
    </div>


</div>
