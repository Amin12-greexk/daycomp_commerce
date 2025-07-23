<x-app-layout>
    <div class="bg-gray-100">
        <div class="container px-4 py-12 mx-auto">
            <h1 class="mb-8 text-3xl font-bold text-center text-gray-800">Checkout</h1>

            <div class="max-w-4xl mx-auto">
                <div class="p-8 bg-white rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-4">Ringkasan Pesanan untuk
                        #{{ $order->order_number }}</h2>
                    <div class="mt-6 space-y-4">
                        @foreach ($order->details as $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/100' }}"
                                        alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-md mr-4">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                <p class="font-semibold text-gray-900">
                                    Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 mt-6 border-t">
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold text-gray-900">Total</p>
                            <p class="text-lg font-semibold text-gray-900">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        <button id="pay-button"
                            class="w-full px-6 py-3 font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                            Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- We now use a named slot instead of @push --}}
    <x-slot name="scripts">
        <!-- Midtrans Snap.js -->
        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script type="text/javascript">
            var payButton = document.getElementById('pay-button');
            payButton.addEventListener('click', function () {
                window.snap.pay('{{ $snapToken }}', {
                    onSuccess: function (result) {
                        fetch('{{ route("checkout.success") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify(result)
                        }).then(() => {
                            window.location.href = '{{ route("customer.dashboard") }}?status=success';
                        });
                    },
                    onPending: function (result) {
                        window.location.href = '{{ route("customer.dashboard") }}?status=pending';
                    },
                    onError: function (result) {
                        alert('Payment failed. Please try again.');
                    },
                    onClose: function () {
                        alert('You closed the popup without finishing the payment.');
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>