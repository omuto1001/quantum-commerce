{{-- resources/views/rider/deliveries/index.blade.php --}}
@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Deliveries</h1>

{{-- My active deliveries - things this rider has accepted but not yet completed --}}
<div class="bg-whitbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40e rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">My Active Deliveries</h2>

    @forelse ($myDeliveries as $item)
        <div class="flex items-center justify-between p-4 border-b last:border-b-0">
            <div>
                <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    From: {{ $item->vendor->shop_name }} • To: {{ $item->order->user->name }}
                </p>
                <p class="text-xs text-gray-500">Deliver to: {{ $item->order->delivery_address }}</p>
            </div>
            <form method="POST" action="{{ route('rider.deliveries.complete', $item) }}">
                @csrf
                @method('PUT')
                <button class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm">Mark Delivered</button>
            </form>
        </div>
    @empty
        <p class="text-gray-500 text-sm">You have no active deliveries right now.</p>
    @endforelse
</div>

{{-- Available deliveries - shipped by vendors, waiting for any rider to accept --}}
<div class="bg-white roundebg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40d-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Available Deliveries</h2>

    @forelse ($availableDeliveries as $item)
        <div class="flex items-center justify-between p-4 border-b last:border-b-0">
            <div>
                <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    From: {{ $item->vendor->shop_name }} • To: {{ $item->order->user->name }}
                </p>
                <p class="text-xs text-gray-500">Deliver to: {{ $item->order->delivery_address }}</p>
            </div>
            <form method="POST" action="{{ route('rider.deliveries.accept', $item) }}">
                @csrf
                <button class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Accept Delivery</button>
            </form>
        </div>
    @empty
        <p class="text-gray-500 text-sm">No deliveries available right now. Check back soon.</p>
    @endforelse
</div>

@endsection