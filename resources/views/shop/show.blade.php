<x-app-layout>
    <div class="bg-white">
        <div class="pt-6">
            {{-- Success Message --}}
            @if (session('success'))
                <div class="max-w-2xl px-4 mx-auto sm:px-6 lg:max-w-7xl lg:px-8">
                    <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                        <span class="font-medium">Success!</span> {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="max-w-2xl px-4 mx-auto mt-6 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:gap-x-8 lg:px-8">
                <div
                    class="overflow-hidden rounded-lg aspect-w-3 aspect-h-4 lg:col-span-2 lg:aspect-w-full lg:aspect-h-full">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400/e2e8f0/e2e8f0?text=No+Image' }}"
                        alt="{{ $product->name }}" class="object-cover object-center w-full h-full">
                </div>
                <div class="p-4 lg:p-0">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $product->name }}</h1>
                    <p class="mt-2 text-3xl tracking-tight text-gray-900">
                        Rp{{ number_format($product->price, 0, ',', '.') }}</p>

                    <div class="mt-6">
                        <h3 class="sr-only">Description</h3>
                        <div class="space-y-6 text-base text-gray-900">
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>

                    {{-- This is the form that will add the product to the cart --}}
                    <form action="{{ route('cart.add', $product) }}" method="POST" enctype="multipart/form-data"
                        class="mt-10">
                        @csrf

                        {{-- Quantity Input --}}
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1"
                                class="w-20 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        @if ($formSchema)
                            <div class="mt-6 space-y-6">
                                @foreach ($formSchema as $field)
                                    <div>
                                        <label for="{{ $field['name'] }}"
                                            class="block text-sm font-medium text-gray-900">{{ $field['label'] }}
                                            @if ($field['required'])
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>
                                        <div class="mt-1">
                                            @switch($field['type'])
                                                @case('text')
                                                    <input type="text" name="custom_fields[{{ $field['name'] }}]"
                                                        id="{{ $field['name'] }}"
                                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                        {{ $field['required'] ? 'required' : '' }}>
                                                @break

                                                @case('textarea')
                                                    <textarea name="custom_fields[{{ $field['name'] }}]" id="{{ $field['name'] }}" rows="3"
                                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                        {{ $field['required'] ? 'required' : '' }}></textarea>
                                                @break

                                                @case('select')
                                                    <select name="custom_fields[{{ $field['name'] }}]"
                                                        id="{{ $field['name'] }}"
                                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                        {{ $field['required'] ? 'required' : '' }}>
                                                        @foreach ($field['options'] as $option)
                                                            <option value="{{ $option['value'] }}">{{ $option['label'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @break

                                                @case('file')
                                                    <input type="file" name="custom_fields[{{ $field['name'] }}]"
                                                        id="{{ $field['name'] }}"
                                                        class="block w-full text-sm text-gray-500 border border-gray-300 rounded-md cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                                        {{ $field['required'] ? 'required' : '' }}>
                                                @break
                                            @endswitch
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <button type="submit"
                            class="flex items-center justify-center w-full px-8 py-3 mt-10 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">+
                            Keranjang</button>
                    </form>

                    {{-- WhatsApp Button --}}
                    @php
                        $whatsappNumber = config('services.whatsapp.number'); // Ambil dari config
                        $productName = urlencode($product->name);
                        $productUrl = urlencode(route('shop.show', $product->slug));
                        $whatsappMessage = urlencode(
                            "Halo, saya ingin bertanya lebih lanjut tentang produk *{$product->name}* ini. Link produk: {$productUrl}",
                        );
                        $whatsappLink = "https://wa.me/{$whatsappNumber}?text={$whatsappMessage}";
                    @endphp

                    @if ($whatsappNumber)
                        <a href="{{ $whatsappLink }}" target="_blank"
                            class="flex items-center justify-center w-full px-8 py-3 mt-4 text-base font-medium text-green-800 bg-green-200 border border-transparent rounded-md hover:bg-green-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.597-3.939-1.597-6.182 0-6.959 5.654-12.614 12.613-12.614 3.542 0 6.845 1.449 9.288 3.992 2.365 2.503 3.656 5.866 3.656 9.407 0 6.96-5.655 12.614-12.614 12.614-1.896 0-3.793-.418-5.441-1.159l-6.096 1.629zm7.04-3.188c1.325.795 2.827 1.218 4.398 1.218 5.698 0 10.315-4.617 10.315-10.316 0-2.923-1.205-5.617-3.111-7.553-1.906-1.936-4.59-3.141-7.513-3.141-5.698 0-10.316 4.617-10.316 10.316 0 1.543.342 3.093 1.054 4.464l-.907 3.315 3.421-.909zM16.501 13.913c-.092-.059-.572-.284-.66-.316-.089-.033-.153-.049-.217.049-.065.099-.25 1.206-.304 1.294-.055.089-.109.099-.202.049-.092-.049-.398-.146-5.074-3.136-.376-.24-.62-.401-.82-.741-.2-.339-.022-.313.15-.487.165-.165.367-.401.448-.601.082-.2.041-.376-.014-.525-.055-.15-.494-1.189-.679-1.619-.186-.431-.371-.366-.525-.376-.15-.009-.328-.009-.504-.009-.176 0-.462.065-.708.304-.246.24-.937.917-.937 2.235 0 1.317.962 2.589 1.096 2.764.134.176 1.839 2.809 4.453 3.918 1.091.45 1.947.729 2.613.917.575.163 1.098.125 1.517.076.471-.059 1.488-.601 1.7-.109.21.492.21 1.449.21 1.564 0 .15-.109.398-.217.492-.109.092-.25.163-.401.246l-.089-.029z" />
                            </svg>
                            Chat Penjual
                        </a>
                    @endif

                </div>
            </div>

            {{-- START NEW SECTION: Related Products --}}
            <section class="max-w-2xl px-4 py-16 mx-auto sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Produk Terkait dari Kategori Ini</h2>

                @if ($relatedProducts->isEmpty())
                    <p class="mt-4 text-gray-600">Tidak ada produk terkait lain dalam kategori ini.</p>
                @else
                    <div class="grid grid-cols-1 mt-6 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div class="group relative">
                                <div
                                    class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                                    <img src="{{ $relatedProduct->image ? asset('storage/' . $relatedProduct->image) : 'https://placehold.co/600x400/e2e8f0/e2e8f0?text=No+Image' }}"
                                        alt="{{ $relatedProduct->name }}"
                                        class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                                </div>
                                <div class="mt-4 flex justify-between">
                                    <div>
                                        <h3 class="text-sm text-gray-700">
                                            <a href="{{ route('shop.show', $relatedProduct->slug) }}">
                                                <span aria-hidden="true" class="absolute inset-0"></span>
                                                {{ $relatedProduct->name }}
                                            </a>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">{{ $relatedProduct->category->name }}</p>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
                                        Rp{{ number_format($relatedProduct->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
            {{-- END NEW SECTION: Related Products --}}

        </div>
    </div>
</x-app-layout>
