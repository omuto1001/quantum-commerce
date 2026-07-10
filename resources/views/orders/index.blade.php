{{-- resources/views/orders/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">My Orders</h1>

    @if ($orders->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-grbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40ay-100 p-10 text-center text-gray-500">
            You haven't placed any orders yet.
            <a href="{{ route('products.index') }}" class="text-green-700 underline block mt-2">Browse products →</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($orders as $order)
                <a href="{{ route('orders.show', $order) }}"
                   class="block bg-white robg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40unded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">Order #{{ $order->id }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $order->items->count() }} item(s) from {{ $order->items->pluck('vendor.shop_name')->unique()->implode(', ') }}</p>
                        </div>
                        @php
    // Compute an overall status for the whole order based on its items:
    // if every item is delivered, the order is "Delivered"
    // if every item is still pending, the order is "Pending"
    // anything in between is "In Progress"
    $itemStatuses = $order->items->pluck('status')->unique();
    if ($itemStatuses->count() === 1 && $itemStatuses->first() === 'delivered') {
        $overallStatus = 'Delivered';
        $badgeClass = 'bg-green-100 text-green-700';
    } elseif ($itemStatuses->count() === 1 && $itemStatuses->first() === 'pending') {
        $overallStatus = 'Pending';
        $badgeClass = 'bg-yellow-100 text-yellow-700';
    } else {
        $overallStatus = 'In Progress';
        $badgeClass = 'bg-blue-100 text-blue-700';
    }
@endphp

<div class="text-right">
    <p class="font-bold text-green-700">UGX {{ number_format($order->total_amount, 2) }}</p>
    <span class="text-xs px-2 py-1 rounded-full mt-1 inline-block {{ $badgeClass }}">
        {{ $overallStatus }}
    </span>
</div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection