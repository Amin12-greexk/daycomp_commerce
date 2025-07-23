@extends('layouts.admin')

@section('content-header', 'All Categories')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.categories.create') }}"
            class="px-4 py-2 font-bold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">+ Category</a>
    </div>
    <div class="overflow-hidden bg-white shadow-md rounded-md">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                class="inline-block ml-4" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center whitespace-nowrap">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
@endsection