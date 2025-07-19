@extends('layouts.admin')

@section('content-header', 'Edit Category')

@section('content')
    <div class="p-6 mt-8 bg-white rounded-md shadow-md">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <label class="text-gray-700" for="name">Name</label>
                <input class="block w-full mt-2 border-gray-200 rounded-md form-input focus:border-indigo-600" type="text"
                    name="name" id="name" value="{{ old('name', $category->name) }}">
                @error('name')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>
            <div class="mt-4">
                <label class="text-gray-700" for="description">Description</label>
                <textarea class="block w-full mt-2 border-gray-200 rounded-md form-textarea focus:border-indigo-600"
                    name="description" id="description">{{ old('description', $category->description) }}</textarea>
                @error('description')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit"
                    class="px-4 py-2 font-bold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Update</button>
            </div>
        </form>
    </div>
@endsection