{{-- resources/views/products/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white roundebg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40d-2xl shadow-sm border border-gray-100 p-8 max-w-3xl">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <div>
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-72 object-cover rounded-xl">
            @else
                <div class="w-full h-72 bg-gray-100 rounded-xl flex items-center justify-center text-5xl">📦</div>
            @endif
        </div>

        <div>
            <p class="text-xs text-gray-400 uppercase font-medium">{{ $product->vendor->shop_name }}</p>
            <h1 class="text-2xl font-bold text-gray-800 mt-1">{{ $product->name }}</h1>
            <p class="text-green-700 font-bold text-2xl mt-3">UGX {{ number_format($product->price, 2) }}</p>
            <p class="text-green-700 font-bold text-2xl mt-3">UGX {{ number_format($product->price, 2) }}</p>

{{-- Average rating summary --}}
@if ($product->reviews->count() > 0)
    <p class="text-sm text-gray-600 mt-2">
        ⭐ {{ number_format($averageRating, 1) }} out of 5 ({{ $product->reviews->count() }} review{{ $product->reviews->count() > 1 ? 's' : '' }})
    </p>
@else
    <p class="text-sm text-gray-400 mt-2">No reviews yet</p>
@endif
            <p class="text-gray-600 mt-4">{{ $product->description ?: 'No description provided.' }}</p>

            <p class="text-sm text-gray-500 mt-4">
    @if ($product->stock > 0)
        ✅ {{ $product->stock }} in stock
    @else
        ❌ Out of stock
    @endif
</p>

@if (auth()->user()->isCustomer())
    <a href="{{ route('messages.vendor.show', $product->vendor) }}" class="text-sm text-indigo-600 underline mt-2 inline-block">
        💬 Message {{ $product->vendor->shop_name }}
    </a>
@endif

@if (auth()->user()->isCustomer() && $product->stock > 0)
                <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-6 flex items-center gap-3">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                           class="w-20 border rounded-lg p-2">
                    <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-6 py-2 rounded-lg font-medium">
                        Add to Cart
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
{{-- Reviews list --}}
@if ($product->reviews->count() > 0)
    <div class="bg-whitebg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40 rounded-2xl shadow-sm border border-gray-100 p-8 max-w-3xl mt-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Customer Reviews</h2>

        @foreach ($product->reviews as $review)
            <div class="border-b last:border-b-0 py-4">
                <div class="flex items-center justify-between">
                    <p class="font-semibold text-gray-800">{{ $review->user->name }}</p>
                    <p class="text-sm">{{ str_repeat('⭐', $review->rating) }}</p>
                </div>
                @if ($review->comment)
                    <p class="text-gray-600 text-sm mt-1">{{ $review->comment }}</p>
                @endif
                <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->format('d M Y') }}</p>
            </div>
        @endforeach
    </div>
@endif
@endsection