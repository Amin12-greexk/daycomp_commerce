@extends('layouts.admin')

@section('content-header', 'All Products')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.products.create') }}"
            class="px-4 py-2 font-bold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">+ Product</a>
    </div>
    <div class="overflow-hidden bg-white shadow-md rounded-md">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Image</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Stock</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-10 h-10 object-cover rounded">
                            @else
                                <span class="text-xs text-gray-500">No Image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                class="inline-block ml-4" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center whitespace-nowrap">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection