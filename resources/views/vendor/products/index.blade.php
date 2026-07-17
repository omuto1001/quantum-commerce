{{-- resources/views/vendor/products/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white roubg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40nded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">My Products</h2>
        <a href="{{ route('vendor.products.create') }}" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-lg text-sm font-medium">
            + Add Product
        </a>
    </div>

    
<div class="overflow-x-auto -mx-6 px-6">
    <table class="w-full text-left border-collapse min-w-[600px]">

        <thead>
            <tr class="border-b text-sm text-gray-500">
    <th class="py-2">Image</th>
    <th>Name</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Rating</th>
    <th>Status</th>
    <th>Action</th>
</tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr class="border-b">
                    <td class="py-3">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 object-cover rounded-lg">
                        @else
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xl">📦</div>
                        @endif
                    </td>
                    <td class="font-medium text-gray-800">{{ $product->name }}</td>
                    <td>UGX {{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <td>
    @if ($product->reviews->count() > 0)
        ⭐ {{ number_format($product->reviews->avg('rating'), 1) }} ({{ $product->reviews->count() }})
    @else
        <span class="text-gray-400 text-xs">No reviews</span>
    @endif
</td>
                        @if ($product->status === 'active')
                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">Active</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-1 rounded-full">Inactive</span>
                        @endif
                    </td>
                    <td class="flex gap-2 py-3">
    <a href="{{ route('vendor.products.show', $product) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">View</a>
    <a href="{{ route('vendor.products.edit', $product) }}" class="bg-gray-700 text-white px-3 py-1 rounded text-sm">Edit</a>
    <form method="POST" action="{{ route('vendor.products.destroy', $product) }}"
          onsubmit="return confirm('Delete this product?');">
        @csrf
        @method('DELETE')
        <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
    </form>
</td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-6 text-center text-gray-500">You haven't added any products yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection