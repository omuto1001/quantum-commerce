{{-- resources/views/rider/deliveries/history.blade.php --}}
@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Delivery History</h1>

<div class="bg-white robg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40unded-2xl shadow-sm border border-gray-100 p-6">
    @forelse ($deliveries as $item)
        <div class="flex items-center justify-between p-4 border-b last:border-b-0">
            <div>
                <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    From: {{ $item->vendor->shop_name }} • To: {{ $item->order->user->name }}
                </p>
                <p class="text-xs text-gray-500">{{ $item->updated_at->format('d M Y, h:i A') }}</p>
            </div>
            <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">Delivered</span>
        </div>
    @empty
        <p class="text-gray-500 text-sm">No completed deliveries yet.</p>
    @endforelse
</div>

@endsection