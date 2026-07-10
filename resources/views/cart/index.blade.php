{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">My Cart</h1>

    @if ($cartItems->isEmpty())
        <div class="bg-white robg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40unded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
            Your cart is empty.
            <a href="{{ route('products.index') }}" class="text-green-700 underline block mt-2">Browse products →</a>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm bbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40order border-gray-100 overflow-hidden">
            @foreach ($cartItems as $item)
                <div class="flex items-center gap-4 p-4 border-b last:border-b-0">

                    {{-- Product image --}}
                    @if ($item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-2xl">📦</div>
                    @endif

                    {{-- Product info --}}
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->product->vendor->shop_name }}</p>
                        <p class="text-green-700 font-bold mt-1">UGX {{ number_format($item->product->price, 2) }}</p>
                    </div>

                    {{-- Quantity update form --}}
                    <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                        @csrf
                        @method('PUT')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                               class="w-16 border rounded-lg p-1 text-center">
                        <button class="text-xs bg-gray-700 text-white px-3 py-1.5 rounded-lg">Update</button>
                    </form>

                    {{-- Subtotal for this line --}}
                    <p class="font-semibold text-gray-800 w-28 text-right">
                        UGX {{ number_format($item->product->price * $item->quantity, 2) }}
                    </p>

                    {{-- Remove button --}}
                    <form method="POST" action="{{ route('cart.remove', $item) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 text-sm hover:underline">Remove</button>
                    </form>
                </div>
            @endforeach
        </div>

        {{-- Total and checkout button --}}
        <div class="bg-white bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40rounded-2xl shadow-sm border border-gray-100 p-6 mt-6 flex items-center justify-between">
            <p class="text-lg font-bold text-gray-800">Total: UGX {{ number_format($total, 2) }}</p>
            <a href="{{ route('checkout.show') }}" class="bg-green-700 hover:bg-green-800 text-white px-6 py-2 rounded-lg font-medium">
                Proceed to Checkout
            </a>
        </div>
    @endif
</div>
@endsection