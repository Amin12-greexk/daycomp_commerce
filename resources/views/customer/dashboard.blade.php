<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('My Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Success/Pending Messages from Checkout --}}
            @if (request()->query('status') == 'success')
                <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    <span class="font-medium">Thank you for your order!</span> Your payment was successful and your order is
                    now being processed.
                </div>
            @endif

            @if (request()->query('status') == 'pending')
                <div class="p-4 mb-6 text-sm text-yellow-700 bg-yellow-100 rounded-lg" role="alert">
                    <span class="font-medium">Your order is pending.</span> We are waiting for payment confirmation. We will
                    notify you once the payment is complete.
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold">My Order History</h3>

                    <div class="mt-4 space-y-6">
                        @forelse ($orders as $order)
                            <div class="p-4 border rounded-lg">
                                <div class="flex flex-col justify-between sm:flex-row">
                                    <div>
                                        <p class="font-bold">Order #{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-600">Placed on:
                                            {{ $order->created_at->format('F j, Y') }}</p>
                                    </div>
                                    <div class="mt-2 text-left sm:mt-0 sm:text-right">
                                        <p class="font-bold">Total: Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Status: <span
                                                class="font-medium capitalize text-{{ $order->payment_status == 'paid' ? 'green' : 'yellow' }}-600">{{ $order->payment_status }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="pt-4 mt-4 border-t">
                                    <h4 class="font-semibold">Items:</h4>
                                    <ul class="mt-2 text-sm text-gray-700 list-disc list-inside">
                                        @foreach ($order->details as $detail)
                                            <li>{{ $detail->product->name }} (x{{ $detail->quantity }})</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">You have not placed any orders yet.</p>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>