<div>
    <div class="mt-20 max-w-screen-xl mx-auto p-4">
        <div class="flex items-center justify-between mb-4">
            <a class="flex items-center justify-center mr-8 text-gray-500 transition-colors duration-200 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                href="/"><svg class="w-3.5 h-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 5H1m0 0 4 4M1 5l4-4"></path>
                </svg>{{ __('Back') }}</a>
            <h4 class="text-2xl font-bold leading-none text-gray-900 dark:text-white">{{ __('Order detail') }}</h4>
        </div>

        <dl class="max-w-md text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
            <div class="flex flex-col pb-3">
                <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">{{ __('Order number') }}</dt>
                <dd class="text-lg font-semibold">{{ $docNum }}</dd>
            </div>
            <div class="flex flex-col py-3">
                <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">{{ __('Customer name') }}</dt>
                <dd class="text-lg font-semibold">{{ $customerName }}</dd>
            </div>
            <div class="flex flex-col pt-3">
                <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">{{ __('Address') }}</dt>
                <dd class="text-lg font-semibold">{{ $address }}</dd>
            </div>
        </dl>



        <div class="relative overflow-x-auto mt-6">
            <table class="w-full text-md text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-md text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="py-3">
                            {{ __('Item code') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Item name') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Quantity') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Price') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documentLines as $documentLine)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $documentLine['itemNumber'] }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $documentLine['itemDescription'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $documentLine['qty'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $currency }} {{ number_format($documentLine['price'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="3"></td>
                        <td class="font-medium text-gray-900 px-6 py-4">
                            {{ __('Vat') }}: {{ $currency }} {{ number_format($vatSum, 2) }}
                        </td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="3"></td>
                        <td class="text-xl text-gray-900 px-6 py-4">
                            {{ __('Total') }}: {{ $currency }} {{ number_format($docTotal, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between mb-6 mt-4">
            <h4 class="text-xl font-bold leading-none text-gray-900 dark:text-white">
                {{ __('Acknowledgement and E-Sign') }}</h4>
        </div>

        @if ($signStatus)
            <dl class="max-w-md text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
                <div class="flex flex-col pb-3">
                    <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">{{ __("Recipient name") }}</dt>
                    <dd class="text-lg font-semibold">{{ $name }}</dd>
                </div>
                <div class="flex flex-col py-3">
                    <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">{{ __("Date and time") }}</dt>
                    <dd class="text-lg font-semibold">{{ $dateTime }}</dd>
                </div>
                <div class="flex flex-col pt-3">
                    <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">{{ __("E-Signature") }}</dt>
                    <dd class="text-lg font-semibold">
                        <img oncontextmenu="return false;" src="{{ asset('storage/signatures/PMSO_'. $docNum . '.png') }}" alt="E-Signature">
                    </dd>
                </div>
            </dl>
        @else    
            <form class="w-full mx-auto" wire:submit="store">
                <div class="mb-5">
                    <div class="flex items-center h-5">
                        <input id="acknowledgement" type="checkbox" wire:model.live="acknowledgement"
                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" />
                        <label for="acknowledgement"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __("Acknowledgement") }}</label>
                    </div>
                    @error('acknowledgement')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>


                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Recipient name") }}</label>
                    <input type="text" id="name" wire:model="name" {{ !$acknowledgement ? 'disabled' : '' }} 
                        class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="singature" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __("Signature") }}</label>
                    <x-signature-pad wire:model="signature" />
                    @error('signature')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button wire:loading.attr="disabled"
                    class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <span wire:loading.class="hidden" wire:target="store">
                        {{ __('Submit') }}
                    </span>
                    <span wire:loading.flex wire:target="store" class="flex items-center justify-center">
                        <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="#E5E7EB" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentColor" />
                        </svg>
                        {{ __('Loading') }}...
                    </span>
                </button>
            </form>
        @endif


    </div>
</div>