{{-- resources/views/checkout/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Checkout</h1>

    {{-- Order summary --}}
    <div class="bg-white roubg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40nded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        @foreach ($cartItems as $item)
            <div class="flex items-center justify-between p-4 border-b last:border-b-0">
                <div>
                    <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                    <p class="text-xs text-gray-500">{{ $item->product->vendor->shop_name }} • Qty: {{ $item->quantity }}</p>
                </div>
                <p class="font-semibold text-gray-800">
                    UGX {{ number_format($item->product->price * $item->quantity, 2) }}
                </p>
            </div>
        @endforeach

        <div class="p-4 bg-gray-50 flex justify-between items-center">
            <p class="font-bold text-gray-800">Total</p>
            <p class="font-bold text-green-700 text-lg">UGX {{ number_format($total, 2) }}</p>
        </div>
    </div>

    {{-- Delivery address form --}}
    <div class="bg-white robg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40unded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Delivery Details</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.process') }}">
    @csrf

    <label class="block font-semibold mb-1">Delivery Address</label>
    <input type="text" name="delivery_address" value="{{ old('delivery_address', auth()->user()->address) }}"
           class="w-full border rounded-lg p-2 mb-6" required>

    <label class="block font-semibold mb-2">Payment Method</label>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">

        <label class="border rounded-lg p-4 flex flex-col items-center gap-2 cursor-pointer has-[:checked]:border-green-700 has-[:checked]:bg-green-50">
            <input type="radio" name="payment_method" value="mobile_money" class="hidden" {{ old('payment_method') == 'mobile_money' ? 'checked' : '' }} required>
            <span class="text-2xl">📱</span>
            <span class="text-sm font-medium">Mobile Money</span>
        </label>

        <label class="border rounded-lg p-4 flex flex-col items-center gap-2 cursor-pointer has-[:checked]:border-green-700 has-[:checked]:bg-green-50">
            <input type="radio" name="payment_method" value="card" class="hidden" {{ old('payment_method') == 'card' ? 'checked' : '' }}>
            <span class="text-2xl">💳</span>
            <span class="text-sm font-medium">Debit/Credit Card</span>
        </label>

        <label class="border rounded-lg p-4 flex flex-col items-center gap-2 cursor-pointer has-[:checked]:border-green-700 has-[:checked]:bg-green-50">
            <input type="radio" name="payment_method" value="cash_on_delivery" class="hidden" {{ old('payment_method', 'cash_on_delivery') == 'cash_on_delivery' ? 'checked' : '' }}>
            <span class="text-2xl">💵</span>
            <span class="text-sm font-medium">Cash on Delivery</span>
        </label>
    </div>

    <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-lg font-medium w-full">
        Continue — UGX {{ number_format($total, 2) }}
    </button>
</form>
    </div>
</div>
@endsection