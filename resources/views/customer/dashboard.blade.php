<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('My Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Status Messages --}}
            @if (session('success'))
                <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
            @endif
            @if (request()->query('status') == 'success')
                <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    <span class="font-medium">Thank you for your order!</span> Your payment was successful.
                </div>
            @endif
            @if (request()->query('status') == 'pending')
                <div class="p-4 mb-6 text-sm text-yellow-700 bg-yellow-100 rounded-lg" role="alert">
                    <span class="font-medium">Your order is pending.</span> We are waiting for payment confirmation.
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold">My Order History</h3>

                    <div class="mt-4 space-y-6">
                        @forelse ($orders as $order)
                            <div class="p-4 border rounded-lg">
                                <div class="flex flex-col justify-between gap-4 sm:flex-row">
                                    <div>
                                        <p class="font-bold">Order #{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-600">Placed on:
                                            {{ $order->created_at->format('F j, Y') }}</p>
                                        <p class="text-sm text-gray-600">
                                            Status: <span
                                                class="font-medium capitalize text-{{ $order->payment_status == 'paid' ? 'green' : ($order->payment_status == 'failed' ? 'red' : 'yellow') }}-600">{{ $order->payment_status }}</span>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Order Status: <span
                                                class="font-medium capitalize text-{{ $order->status == 'completed' ? 'green' : ($order->status == 'cancelled' ? 'red' : ($order->status == 'processing' ? 'blue' : 'gray')) }}-600">{{ $order->status }}</span>
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-start sm:items-end">
                                        <p class="font-bold">Total:
                                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                        </p>

                                        @if ($order->payment_status == 'unpaid' || $order->payment_status == 'failed')
                                            <form action="{{ route('orders.retryPayment', $order) }}" method="POST"
                                                class="mt-2">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1 text-sm font-semibold text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700">
                                                    Pay Now
                                                </button>
                                            </form>
                                        @endif
                                    </div>
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

    {{-- This now uses the named slot to match your layout file --}}
    <x-slot name="scripts">
        @if (session('snapToken'))
            <!-- Midtrans Snap.js -->
            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
            </script>
            <script>
                window.snap.pay('{{ session('snapToken') }}', {
                    onSuccess: function(result) {
                        fetch('{{ route('checkout.success') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(result)
                        }).then(() => {
                            window.location.href = '{{ route('customer.dashboard') }}?status=success';
                        });
                    },
                    onPending: function(result) {
                        window.location.href = '{{ route('customer.dashboard') }}?status=pending';
                    },
                    onError: function(result) {
                        alert('Payment failed. Please try again.');
                    },
                    onClose: function() {
                        window.location.href = '{{ route('customer.dashboard') }}';
                    }
                });
            </script>
        @endif
    </x-slot>
</x-app-layout>
