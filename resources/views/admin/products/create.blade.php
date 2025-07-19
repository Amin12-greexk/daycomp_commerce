@extends('layouts.admin')

@section('content-header', 'Add New Product')

@section('content')
    <div class="p-6 mt-8 bg-white rounded-md shadow-md">
        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <span class="font-medium">Oops! Something went wrong.</span>
                <ul class="mt-1.5 ml-4 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Basic Product Fields --}}
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <div>
                    <label class="text-gray-700" for="name">Name</label>
                    <input
                        class="block w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        type="text" name="name" id="name" value="{{ old('name') }}">
                </div>
                <div>
                    <label class="text-gray-700" for="category_id">Category</label>
                    <select
                        class="block w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        name="category_id" id="category_id">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-gray-700" for="price">Price</label>
                    <input
                        class="block w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        type="number" step="0.01" name="price" id="price" value="{{ old('price') }}">
                </div>
                <div>
                    <label class="text-gray-700" for="stock">Stock</label>
                    <input
                        class="block w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        type="number" name="stock" id="stock" value="{{ old('stock') }}">
                </div>
            </div>
            <div class="mt-4">
                <label class="text-gray-700" for="description">Description</label>
                <textarea
                    class="block w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    name="description" id="description">{{ old('description') }}</textarea>
            </div>
            <div class="mt-4">
                <label class="text-gray-700" for="image">Product Image</label>
                <input
                    class="block w-full mt-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                    type="file" name="image" id="image">
            </div>

            <hr class="my-6">

            <!-- Custom Form Builder -->
            <div x-data="formBuilder()"
                x-init="init( {{ json_encode(old('custom_form_schema') ? json_decode(old('custom_form_schema')) : []) }} )">
                <h3 class="text-lg font-medium text-gray-700">Custom Form Builder</h3>
                <p class="mt-1 text-sm text-gray-500">Add custom options for this product.</p>

                <textarea name="custom_form_schema" x-model="jsonOutput" class="hidden"></textarea>

                <div class="mt-4 space-y-4">
                    <template x-for="(field, index) in fields" :key="index">
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                            <div class="flex items-center justify-between">
                                <h4 class="font-medium text-gray-800">Field <span x-text="index + 1"></span></h4>
                                <button @click.prevent="removeField(index)" type="button"
                                    class="px-2 py-1 text-xs font-bold text-white bg-red-500 rounded hover:bg-red-700">&times;
                                    Remove</button>
                            </div>
                            <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2">
                                <div>
                                    <label class="text-sm text-gray-700">Label</label>
                                    <input x-model="field.label" @input.debounce.500ms="updateJson()" type="text"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                                        placeholder="e.g., T-Shirt Size">
                                </div>
                                <div>
                                    <label class="text-sm text-gray-700">Name (no spaces)</label>
                                    <input x-model="field.name" @input.debounce.500ms="updateJson()" type="text"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                                        placeholder="e.g., tshirt_size">
                                </div>
                                <div>
                                    <label class="text-sm text-gray-700">Type</label>
                                    <select x-model="field.type" @change="updateJson()"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                        <option value="text">Text</option>
                                        <option value="select">Select Dropdown</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="file">File Upload</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="flex items-center mt-6 space-x-2 text-sm text-gray-700">
                                        <input x-model="field.required" @change="updateJson()" type="checkbox"
                                            class="rounded">
                                        <span>Required</span>
                                    </label>
                                </div>
                            </div>

                            <div x-show="field.type === 'select'" class="pt-4 mt-4 border-t border-gray-200">
                                <h5 class="font-medium text-sm">Dropdown Options</h5>
                                <div class="mt-2 space-y-2">
                                    <template x-for="(option, optionIndex) in field.options" :key="optionIndex">
                                        <div class="flex items-center space-x-2">
                                            <input x-model="option.label" @input.debounce.500ms="updateJson()" type="text"
                                                class="w-1/2 mt-1 border-gray-300 rounded-md shadow-sm"
                                                placeholder="Label (e.g., Small)">
                                            <input x-model="option.value" @input.debounce.500ms="updateJson()" type="text"
                                                class="w-1/2 mt-1 border-gray-300 rounded-md shadow-sm"
                                                placeholder="Value (e.g., sm)">
                                            <button @click.prevent="removeOption(index, optionIndex)" type="button"
                                                class="px-2 py-1 text-xs text-red-500">&times;</button>
                                        </div>
                                    </template>
                                </div>
                                <button @click.prevent="addOption(index)" type="button"
                                    class="px-2 py-1 mt-2 text-xs font-medium text-indigo-600 border border-indigo-600 rounded hover:bg-indigo-50">Add
                                    Option</button>
                            </div>
                        </div>
                    </template>
                </div>

                <button @click.prevent="addField()" type="button"
                    class="px-4 py-2 mt-4 font-bold text-white bg-gray-600 rounded-md hover:bg-gray-700">Add Field</button>
            </div>
            <!-- End Custom Form Builder -->

            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="px-6 py-2 font-bold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Create
                    Product</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function formBuilder() {
            return {
                fields: [],
                jsonOutput: '',
                init(initialData) {
                    this.fields = initialData || [];
                    this.updateJson();
                },
                updateJson() {
                    this.jsonOutput = JSON.stringify(this.fields);
                },
                addField() {
                    this.fields.push({
                        name: '',
                        label: '',
                        type: 'text',
                        required: false,
                        options: []
                    });
                    this.updateJson();
                },
                removeField(index) {
                    this.fields.splice(index, 1);
                    this.updateJson();
                },
                addOption(fieldIndex) {
                    if (this.fields[fieldIndex].options === undefined) {
                        this.fields[fieldIndex].options = [];
                    }
                    this.fields[fieldIndex].options.push({ label: '', value: '' });
                    this.updateJson();
                },
                removeOption(fieldIndex, optionIndex) {
                    this.fields[fieldIndex].options.splice(optionIndex, 1);
                    this.updateJson();
                }
            }
        }
    </script>
@endpush