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

            <!-- Main Product Info -->
            <div class="max-w-2xl px-4 mx-auto mt-6 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:gap-x-8 lg:px-8">
                <div class="overflow-hidden rounded-lg aspect-w-3 aspect-h-4 lg:col-span-2 lg:aspect-w-full lg:aspect-h-full">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400/e2e8f0/e2e8f0?text=No+Image' }}" alt="{{ $product->name }}" class="object-cover object-center w-full h-full">
                </div>
                <div class="p-4 lg:p-0">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $product->name }}</h1>
                    <p class="mt-2 text-3xl tracking-tight text-gray-900">Rp{{ number_format($product->price, 0, ',', '.') }}</p>

                    <div class="mt-6">
                        <h3 class="sr-only">Description</h3>
                        <div class="space-y-6 text-base text-gray-900">
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>

                    {{-- This is the form that will add the product to the cart --}}
                    <form action="{{ route('cart.add', $product) }}" method="POST" enctype="multipart/form-data" class="mt-10">
                        @csrf

                        {{-- Quantity Input --}}
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" class="w-20 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Custom Form Fields -->
                        @if ($formSchema)
                            <div class="mt-6 space-y-6">
                                @foreach ($formSchema as $field)
                                    <div>
                                        <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-900">{{ $field['label'] }} @if($field['required']) <span class="text-red-500">*</span> @endif</label>
                                        <div class="mt-1">
                                            @switch($field['type'])
                                                @case('text')
                                                    <input type="text" name="custom_fields[{{ $field['name'] }}]" id="{{ $field['name'] }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{ $field['required'] ? 'required' : '' }}>
                                                    @break

                                                @case('textarea')
                                                    <textarea name="custom_fields[{{ $field['name'] }}]" id="{{ $field['name'] }}" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{ $field['required'] ? 'required' : '' }}></textarea>
                                                    @break

                                                @case('select')
                                                    <select name="custom_fields[{{ $field['name'] }}]" id="{{ $field['name'] }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{ $field['required'] ? 'required' : '' }}>
                                                        @foreach ($field['options'] as $option)
                                                            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    @break

                                                @case('file')
                                                    <input type="file" name="custom_fields[{{ $field['name'] }}]" id="{{ $field['name'] }}" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-md cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" {{ $field['required'] ? 'required' : '' }}>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <button type="submit" class="flex items-center justify-center w-full px-8 py-3 mt-10 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Add to cart</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>