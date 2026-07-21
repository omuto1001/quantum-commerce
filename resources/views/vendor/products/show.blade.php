{{-- resources/views/vendor/products/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/402xl shadow-sm border border-gray-100 p-8 max-w-2xl">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Product Details</h2>
        <span class="text-xs font-semibold px-3 py-1 rounded-full
              {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
            {{ ucfirst($product->status) }}
        </span>
    </div>

    {{-- Product image, or a placeholder box if none was uploaded --}}
    @if ($product->image)
        <img src="{{ asset('storage/' . $product->image) }}" class="w-40 h-40 object-cover rounded-xl mb-6">
    @else
        <div class="w-40 h-40 bg-gray-100 rounded-xl flex items-center justify-center text-4xl mb-6">📦</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div>
            <p class="text-xs text-gray-400 uppercase font-medium">Product Name</p>
            <p class="text-gray-800 font-medium mt-1 text-lg">{{ $product->name }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 uppercase font-medium">Price</p>
            <p class="text-green-700 font-bold mt-1 text-lg">UGX {{ number_format($product->price, 2) }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 uppercase font-medium">Stock Available</p>
            <p class="text-gray-800 font-medium mt-1">{{ $product->stock }} units</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 uppercase font-medium">Date Added</p>
            <p class="text-gray-800 font-medium mt-1">{{ $product->created_at->format('d M Y') }}</p>
        </div>
        <div class="sm:col-span-2">
            <p class="text-xs text-gray-400 uppercase font-medium">Description</p>
            <p class="text-gray-800 mt-1">{{ $product->description ?: 'No description added.' }}</p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3">
    <a href="{{ route('vendor.products.edit', $product) }}" class="bg-gray-700 text-white px-6 py-2 rounded-lg text-sm text-center">Edit Product</a>
    <a href="{{ route('vendor.products.index') }}" class="bg-gray-200 px-6 py-2 rounded-lg text-sm text-center">Back to My Products</a>
    <form method="POST" action="{{ route('vendor.products.destroy', $product) }}"
          onsubmit="return confirm('Delete this product?');" class="w-full sm:w-auto">
        @csrf
        @method('DELETE')
        <button class="bg-red-600 text-white px-6 py-2 rounded-lg text-sm w-full">Delete</button>
    </form>
</div>
    {{-- Reviews on this product --}}
<div class="border-t pt-6 mt-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        Customer Reviews
        @if ($product->reviews->count() > 0)
            <span class="text-sm font-normal text-gray-500">
                (⭐ {{ number_format($averageRating, 1) }} average from {{ $product->reviews->count() }} review{{ $product->reviews->count() > 1 ? 's' : '' }})
            </span>
        @endif
    </h3>

    @forelse ($product->reviews as $review)
        <div class="border-b last:border-b-0 py-3">
            <div class="flex items-center justify-between">
                <p class="font-semibold text-gray-800">{{ $review->user->name }}</p>
                <p class="text-sm">{{ str_repeat('⭐', $review->rating) }}</p>
            </div>
            @if ($review->comment)
                <p class="text-gray-600 text-sm mt-1">{{ $review->comment }}</p>
            @endif
            <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->format('d M Y') }}</p>
        </div>
    @empty
        <p class="text-gray-500 text-sm">No reviews yet for this product.</p>
    @endforelse
</div>
</div>
@endsection