{{-- resources/views/orders/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Order #{{ $order->id }}</h1>
    <p class="text-gray-500 mb-6">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
    <div class="mb-4">
    <span class="text-xs px-3 py-1 rounded-full
          @if ($order->payment_status === 'paid') bg-green-100 text-green-700
          @elseif ($order->payment_status === 'failed') bg-red-100 text-red-700
          @else bg-yellow-100 text-yellow-700
          @endif">
        Payment: {{ ucfirst($order->payment_status) }} ({{ str_replace('_', ' ', ucfirst($order->payment_method)) }})
    </span>

    @if ($order->payment_status === 'pending' && $order->payment_method !== 'cash_on_delivery')
        <a href="{{ route('payment.initiate', $order) }}" class="text-xs text-green-700 underline ml-2">Retry Payment →</a>
    @endif
</div>
    <div class="bg-white rounded-2bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        @foreach ($order->items as $item)
    <div class="p-4 border-b last:border-b-0">
        <div class="flex items-center justify-between">
            <div>
                <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                <p class="text-xs text-gray-500">{{ $item->vendor->shop_name }} • Qty: {{ $item->quantity }}</p>
            </div>
            <div class="text-right">
                <p class="font-semibold text-gray-800">UGX {{ number_format($item->price * $item->quantity, 2) }}</p>
                <span class="text-xs px-2 py-1 rounded-full mt-1 inline-block
                      @if ($item->status === 'delivered') bg-green-100 text-green-700
                      @elseif ($item->status === 'shipped') bg-blue-100 text-blue-700
                      @elseif ($item->status === 'confirmed') bg-purple-100 text-purple-700
                      @else bg-yellow-100 text-yellow-700
                      @endif">
                    {{ ucfirst($item->status) }}
                </span>
            </div>
        </div>

        {{-- Only show review options once the item is delivered --}}
        @if ($item->status === 'delivered')
            @if ($item->review)
                <p class="text-xs text-yellow-600 mt-2">
                    You rated this {{ $item->review->rating }}/5 ⭐
                </p>
            @else
                <a href="{{ route('reviews.create', $item) }}" class="text-xs text-green-700 underline mt-2 inline-block">
                    Leave a review →
                </a>
            @endif
        @endif
    </div>
@endforeach

        <div class="p-4 bg-gray-50 flex justify-between items-center">
            <p class="font-bold text-gray-800">Total</p>
            <p class="font-bold text-green-700 text-lg">UGX {{ number_format($order->total_amount, 2) }}</p>
        </div>
    </div>

    <div class="bg-white roundbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40ed-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-xs text-gray-400 uppercase font-medium">Delivery Address</p>
        <p class="text-gray-800 font-medium mt-1">{{ $order->delivery_address }}</p>
    </div>

    <a href="{{ route('orders.index') }}" class="text-green-700 underline mt-6 inline-block">← Back to My Orders</a>
</div>
@endsection