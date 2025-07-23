@extends('layouts.admin')

@section('content-header')
    <span>Order Details: #{{ $order->order_number }}</span>
@endsection

@section('content')
    {{-- Session Success Message --}}
    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-8 lg:flex-row">
        <!-- Order Details -->
        <div class="w-full lg:w-2/3">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-lg font-semibold border-b pb-4">Items in this Order</h3>
                <div class="mt-4 divide-y divide-gray-200">
                    @foreach($order->details as $detail)
                        <div class="py-4 flex">
                            <img src="{{ $detail->product->image ? asset('storage/' . $detail->product->image) : 'https://placehold.co/100' }}"
                                alt="{{ $detail->product->name }}" class="w-20 h-20 object-cover rounded-md mr-4">
                            <div class="flex-grow">
                                <p class="font-semibold text-gray-900">{{ $detail->product->name }}</p>
                                <p class="text-sm text-gray-600">Qty: {{ $detail->quantity }} @
                                    Rp{{ number_format($detail->price, 0, ',', '.') }}</p>

                                {{-- Display Custom Form Data --}}
                                @if($detail->custom_form_data)
                                    <div class="mt-2 pt-2 border-t text-xs text-gray-500">
                                        <p class="font-semibold">Custom Options:</p>
                                        @foreach($detail->custom_form_data as $key => $value)
                                            <div class="flex">
                                                <p class="w-1/3 font-medium capitalize">{{ str_replace('_', ' ', $key) }}</p>
                                                <p class="w-2/3">{{ $value }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <p class="font-semibold text-gray-900">
                                Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Customer & Order Summary -->
        <div class="w-full lg:w-1/3">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-lg font-semibold border-b pb-4">Summary</h3>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order Status:</span>
                        <span class="font-semibold capitalize">{{ $order->status }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Status:</span>
                        <span class="font-semibold capitalize">{{ $order->payment_status }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order Date:</span>
                        <span class="font-semibold">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between pt-4 border-t font-bold text-lg">
                        <span>Total Amount:</span>
                        <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <h3 class="mt-6 text-lg font-semibold border-b pb-4">Customer Details</h3>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Name:</span>
                        <span class="font-semibold">{{ $order->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-semibold">{{ $order->user->email }}</span>
                    </div>
                </div>

                {{-- Update Status Form --}}
                <div class="pt-6 mt-6 border-t">
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        <label for="status" class="block text-sm font-medium text-gray-700">Update Order Status</label>
                        <div class="flex mt-1">
                            <select id="status" name="status"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Dikemas
                                </option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan
                                </option>
                            </select>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 ml-3 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection