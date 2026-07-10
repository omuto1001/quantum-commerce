{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Shop Products</h1>
    <p class="text-gray-500 mt-1">Browse products from all our vendors.</p>
</div>



{{-- Search + category filter bar --}}
<form method="GET" action="{{ route('products.index') }}" class="flex flex-col sm:flex-row gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
           class="flex-1 border rounded-lg p-2">

    <select name="category" class="border rounded-lg p-2">
        <option value="">All Categories</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <button class="bg-green-700 text-white px-6 py-2 rounded-lg">Search</button>

    @if (request('search') || request('category'))
        <a href="{{ route('products.index') }}" class="bg-gray-200 px-6 py-2 rounded-lg text-center">Clear</a>
    @endif
</form>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
    @forelse ($products as $product)
        <a href="{{ route('products.show', $product) }}"
           class="bg-white roubg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40nded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">

            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-40 object-cover">
            @else
                <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-4xl">📦</div>
            @endif

            <div class="p-4">
                <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $product->vendor->shop_name }}</p>
                <p class="text-green-700 font-bold mt-2">UGX {{ number_format($product->price, 2) }}</p>
            </div>
        </a>
    @empty
        <div class="col-span-4 text-center text-gray-500 py-10">
            No products available yet. Check back soon!
        </div>
    @endforelse
</div>
@endsection