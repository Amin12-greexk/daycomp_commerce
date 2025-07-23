@extends('layouts.admin')

@section('content-header', 'All Orders')

@section('content')
    <div class="overflow-hidden bg-white shadow-md rounded-md">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Order #</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Payment
                        Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Order Status
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex px-2 text-xs font-semibold leading-5 capitalize rounded-full {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $order->payment_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $order->status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order) }}"
                                class="text-indigo-600 hover:text-indigo-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center whitespace-nowrap">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endsection