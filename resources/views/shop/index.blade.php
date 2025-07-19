<x-app-layout>
    <div class="bg-white">
        <!-- Hero Section -->
        <div class="relative bg-gray-900">
            <div aria-hidden="true" class="absolute inset-0 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=2070&auto=format&fit=crop"
                    alt="Hero background" class="object-cover object-center w-full h-full">
            </div>
            <div aria-hidden="true" class="absolute inset-0 bg-gray-900 opacity-50"></div>

            <div class="relative flex flex-col items-center max-w-3xl px-6 py-32 mx-auto text-center">
                <h1 class="text-4xl font-bold tracking-tight text-white lg:text-6xl">Find Your Style</h1>
                <p class="mt-4 text-xl text-white">Discover our latest collection of products, designed to fit your
                    lifestyle. Quality and style, delivered to your door.</p>
                <a href="#products-section"
                    class="inline-block px-8 py-3 mt-8 text-base font-medium text-gray-900 bg-white border border-transparent rounded-md hover:bg-gray-100">Shop
                    Now</a>
            </div>
        </div>

        <!-- Products Section -->
        <div id="products-section" class="max-w-2xl px-4 py-16 mx-auto sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                {{ request()->query('category') ? Str::title(str_replace('-', ' ', request()->query('category'))) : 'All Products' }}
            </h2>

            <div class="grid grid-cols-1 mt-6 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @forelse ($products as $product)
                    <div class="relative group">
                        <div
                            class="w-full overflow-hidden bg-gray-200 rounded-md aspect-h-1 aspect-w-1 lg:aspect-none group-hover:opacity-75 lg:h-80">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400/e2e8f0/e2e8f0?text=No+Image' }}"
                                alt="{{ $product->name }}"
                                class="object-cover object-center w-full h-full lg:h-full lg:w-full">
                        </div>
                        <div class="flex justify-between mt-4">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="{{ route('shop.show', $product->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $product->category->name }}</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Rp{{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4">
                        <div class="py-16 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
                            <p class="mt-1 text-sm text-gray-500">There are no products matching the selected category.</p>
                            <div class="mt-6">
                                <a href="{{ route('shop.index') }}"
                                    class="text-base font-medium text-indigo-600 hover:text-indigo-500">
                                    Browse all products
                                    <span aria-hidden="true"> &rarr;</span>
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