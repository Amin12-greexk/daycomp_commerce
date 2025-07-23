<x-app-layout>
    <div class="bg-gray-100">
        <div class="container px-4 py-12 mx-auto">
            <h1 class="mb-8 text-3xl font-bold text-center text-gray-800">Keranjang Belanja MU</h1>

            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif
             @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
            @endif

            @if (count($cartItems) > 0)
                <div class="flex flex-col gap-8 lg:flex-row">
                    <!-- Cart Items -->
                    <div class="w-full lg:w-2/3">
                        <div class="bg-white rounded-lg shadow-md">
                            <ul class="divide-y divide-gray-200">
                                @foreach ($cartItems as $rowId => $item)
                                    <li class="flex flex-col p-6 space-y-4 sm:flex-row sm:space-y-0 sm:space-x-6">
                                        <img class="flex-shrink-0 object-cover w-24 h-24 border border-gray-200 rounded-md sm:w-32 sm:h-32" src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://placehold.co/400/e2e8f0/e2e8f0?text=No+Image' }}" alt="{{ $item['name'] }}">
                                        <div class="flex flex-col justify-between flex-grow">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900"><a href="#">{{ $item['name'] }}</a></h3>
                                                
                                                {{-- Display Custom Form Data --}}
                                                @if (!empty($item['custom_form_data']))
                                                    <div class="mt-2 text-sm text-gray-600">
                                                        @foreach ($item['custom_form_data'] as $key => $value)
                                                            <p><strong class="capitalize">{{ str_replace('_', ' ', $key) }}:</strong> {{ is_string($value) && strlen($value) > 30 ? Str::limit($value, 30) : $value }}</p>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between mt-4">
                                                <p class="text-lg font-bold text-gray-900">Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                                                <div class="flex items-center">
                                                    {{-- Update Quantity Form --}}
                                                    <form action="{{ route('cart.update', $rowId) }}" method="POST" class="flex items-center">
                                                        @csrf
                                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 h-10 text-center border-gray-300 rounded-md shadow-sm">
                                                        <button type="submit" class="ml-2 text-xs text-indigo-600 hover:underline">Update</button>
                                                    </form>
                                                    {{-- Remove Item Link --}}
                                                    <a href="{{ route('cart.remove', $rowId) }}" class="ml-4 font-medium text-red-600 hover:text-red-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="w-full lg:w-1/3">
                        <div class="p-6 bg-white rounded-lg shadow-md">
                            <h2 class="text-lg font-medium text-gray-900">Ringkasan pesanan</h2>
                            <div class="flex justify-between mt-6 text-gray-600">
                                <span>Subtotal</span>
                                <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between mt-2 text-gray-600">
                                <span>Pengiriman</span>
                                <span>dihitung saat checkout</span>
                            </div>
                            <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-200">
                                <dt class="text-base font-medium text-gray-900">Order total</dt>
                                <dd class="text-base font-medium text-gray-900">Rp{{ number_format($total, 0, ',', '.') }}</dd>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="flex items-center justify-center w-full px-6 py-3 mt-6 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">Checkout</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="py-16 text-center bg-white rounded-lg shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <h2 class="mt-2 text-xl font-semibold text-gray-800">Keranjang Anda kosong</h2>
                    <p class="mt-1 text-gray-500">Kamu belum menambahkan apa pun ke keranjang belanja.</p>
                    <a href="{{ route('shop.index') }}" class="inline-block px-6 py-2 mt-4 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Belanja Sekarang</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>