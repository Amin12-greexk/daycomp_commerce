<x-app-layout>
    <div class="bg-white">
        {{-- Hero Section --}}
        <div class="relative overflow-hidden bg-white min-h-[500px] lg:min-h-[600px] xl:min-h-[650px] pb-0">
            {{-- Background image dan overlay --}}
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1616866160166-f76101c57375?q=80&w=2070&auto=format&fit=crop"
                    alt="Hero background" class="object-cover object-center w-full h-full opacity-30">
                <div class="absolute inset-0 bg-white opacity-90"></div>
            </div>

            {{-- Konten utama --}}
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
                {{-- Kolom kiri (teks) --}}
                <div class="w-full lg:w-[45%] xl:w-[40%] text-center lg:text-left mb-12 lg:mb-0 pr-4 lg:pr-12">
                    <h1 class="text-5xl font-extrabold tracking-tight text-gray-900 sm:text-6xl lg:text-7xl">
                        Design Menarik<br>Harga terjangkau!
                    </h1>
                    <p class="mt-6 text-xl text-gray-700 max-w-lg mx-auto lg:mx-0">
                        Berbagai pilihan design yang Menarik. Daycomp Percetakan
                    </p>
                    <a href="#products-section"
                        class="inline-block px-10 py-4 mt-12 text-lg font-semibold text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 transition duration-300 ease-in-out shadow-lg">
                        Shop Now
                    </a>
                </div>

                {{-- Kolom kanan (gambar undangan) --}}
                <div
                    class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2
                        w-[380px] h-[380px] xl:w-[430px] xl:h-[430px] 2xl:w-[480px] 2xl:h-[480px] mr-4">
                    <div class="relative w-full h-full">
                        {{-- Gambar utama --}}
                        <img src="/images/1.jpeg" alt="Main Undangan sample"
                            class="absolute inset-0 w-full h-full object-cover rounded-lg shadow-xl rotate-3 transform origin-bottom-right">

                        {{-- Gambar kedua --}}
                        <img src="/images/2.jpeg" alt="Second Undangan sample"
                            class="absolute w-[60%] h-[60%] object-cover rounded-lg shadow-xl -left-1/4 top-1/4 transform -rotate-6 origin-top-left">

                        {{-- Gambar ketiga --}}
                        <img src="/images/3.jpeg" alt="Third Undangan sample"
                            class="absolute w-[40%] h-[40%] object-cover rounded-lg shadow-xl right-0 bottom-0 transform rotate-12 origin-bottom-left">
                    </div>
                </div>
            </div>
        </div>

        {{-- Products Section --}}
        <div id="products-section" class="max-w-2xl px-4 pt-4 pb-16 mx-auto sm:px-6 sm:pb-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-8">
                {{ request()->query('category') ? Str::title(str_replace('-', ' ', request()->query('category'))) : 'All Products' }}
            </h2>

            <div class="grid grid-cols-1 mt-6 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @forelse ($products as $product)
                    <div
                        class="relative group bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                        <div class="w-full overflow-hidden bg-gray-100 aspect-w-1 aspect-h-1 lg:h-80">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400/e2e8f0/e2e8f0?text=No+Image' }}"
                                alt="{{ $product->name }}" class="object-cover object-center w-full h-full">
                        </div>
                        <div class="p-4 flex flex-col justify-between h-auto">
                            <div>
                                <h3 class="text-md font-semibold text-gray-800">
                                    <a href="{{ route('shop.show', $product->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $product->category->name }}</p>
                            </div>
                            <p class="mt-3 text-lg font-bold text-indigo-600">
                                Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="py-16 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-4 text-xl font-bold text-gray-900">No products found</h3>
                            <p class="mt-2 text-md text-gray-600">There are no products matching the selected
                                category.</p>
                            <div class="mt-8">
                                <a href="{{ route('shop.index') }}"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Browse all products
                                    <span aria-hidden="true" class="ml-2"> &rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
