@extends('layouts.admin')

@section('content-header', 'Dashboard')

@section('content')
    <div class="mt-8">
        <div class="p-6 bg-white rounded-md shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800">Admin Dashboard</h2>
            <p class="mt-2 text-gray-600">Selamat datang di panel admin Anda! Berikut adalah ringkasan performa toko Anda.
            </p>
        </div>

        {{-- Statistics Cards Section --}}
        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Total Products Card --}}
            <a href="{{ route('admin.products.index') }}"
                class="block bg-white rounded-lg shadow-lg p-6 flex items-center justify-between transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Produk</p>
                    <p class="text-4xl font-extrabold text-indigo-600 mt-1">{{ $totalProducts }}</p>
                </div>
                <div class="p-3 bg-indigo-100 rounded-full">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </a>

            {{-- Total Categories Card --}}
            <a href="{{ route('admin.categories.index') }}"
                class="block bg-white rounded-lg shadow-lg p-6 flex items-center justify-between transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Kategori</p>
                    <p class="text-4xl font-extrabold text-green-600 mt-1">{{ $totalCategories }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7m-4 4h-4m4 0h4m-4 0v4m0 0h-4m4 0v-4m-4 4h-4m4 0v-4m-4 4h-4m4 0v-4">
                        </path>
                    </svg>
                </div>
            </a>

            {{-- Total Orders Card --}}
            <a href="{{ route('admin.orders.index') }}"
                class="block bg-white rounded-lg shadow-lg p-6 flex items-center justify-between transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
                    <p class="text-4xl font-extrabold text-yellow-600 mt-1">{{ $totalOrders }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
            </a>

            {{-- Total Customer Accounts Card --}}
            <a href="{{ route('admin.users.index') }}"
                class="block bg-white rounded-lg shadow-lg p-6 flex items-center justify-between transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pelanggan</p>
                    <p class="text-4xl font-extrabold text-blue-600 mt-1">{{ $totalCustomers }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.185-.015-.368-.044-.543m-.044.543a3 3 0 00-5.356-1.857M12 8a4 4 0 11-8 0 4 4 0 018 0zm0 0v1.5a2.5 2.5 0 005 0V8a4 4 0 11-8 0z">
                        </path>
                    </svg>
                </div>
            </a>

        </div>

        {{-- You can add more sections here for charts, recent activities, etc. --}}

    </div>
@endsection
