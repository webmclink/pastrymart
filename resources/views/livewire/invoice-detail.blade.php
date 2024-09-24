<div>
    <div class="mt-20 max-w-screen-xl mx-auto p-4">
        <div class="flex items-center justify-between mb-4">
            <a class="flex items-center justify-center mr-8 text-gray-500 transition-colors duration-200 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                href="/"><svg class="w-3.5 h-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 5H1m0 0 4 4M1 5l4-4"></path>
                </svg>{{ __("Back") }}</a>
            <h4 class="text-2xl font-bold leading-none text-gray-900 dark:text-white">{{ __('Invoice detail') }}</h4>
        </div>

        <dl class="max-w-md text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
            <div class="flex flex-col pb-3">
                <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">{{ __("Invoice number") }}</dt>
                <dd class="text-lg font-semibold">{{ $docNum }}</dd>
            </div>
            <div class="flex flex-col py-3">
                <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">{{ __("Customer name") }}</dt>
                <dd class="text-lg font-semibold">{{ $customerName }}</dd>
            </div>
            <div class="flex flex-col pt-3">
                <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">{{ __("Address") }}</dt>
                <dd class="text-lg font-semibold">{{ $address }}</dd>
            </div>
        </dl>



        <div class="relative overflow-x-auto mt-6">
            <table class="w-full text-md text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-md text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="py-3">
                            {{ __("Item code") }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __("Item name") }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __("Quantity") }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __("Price") }}
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
                    <tr>
                        <td colspan="4" class="font-medium text-gray-900 text-right px-6 py-4">
                            {{ __("Vat") }}: {{ $currency }} {{ number_format($vatSum, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="font-medium text-gray-900 text-right px-6 py-4">
                            {{ __("Total") }}: {{ $currency }} {{ number_format($docTotal, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between mb-4">
            <h4 class="text-2xl font-bold leading-none text-gray-900 dark:text-white">{{ __("Acknowledgement and E-Sign") }}</h4>
        </div>
    </div>
</div>
