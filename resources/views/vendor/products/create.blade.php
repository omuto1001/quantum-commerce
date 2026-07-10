{{-- resources/views/vendor/products/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-whitebg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40 rounded-2xl shadow-sm border border-gray-100 p-8 max-w-xl">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Product</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- enctype is required for file uploads to work --}}
    <form method="POST" action="{{ route('vendor.products.store') }}" enctype="multipart/form-data">
        @csrf

        <label class="block font-semibold mb-1">Product Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg p-2 mb-4" required>

        <label class="block font-semibold mb-1">Category</label>
<select name="category_id" class="w-full border rounded-lg p-2 mb-4">
    <option value="">-- Select a category --</option>
    @foreach ($categories as $category)
        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
            {{ $category->name }}
        </option>
    @endforeach
</select>


        <label class="block font-semibold mb-1">Description</label>
        <textarea name="description" class="w-full border rounded-lg p-2 mb-4">{{ old('description') }}</textarea>

        <label class="block font-semibold mb-1">Price (UGX)</label>
        <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="w-full border rounded-lg p-2 mb-4" required>

        <label class="block font-semibold mb-1">Stock Quantity</label>
        <input type="number" name="stock" value="{{ old('stock') }}" class="w-full border rounded-lg p-2 mb-4" required>

        <label class="block font-semibold mb-1">Product Image</label>
        <input type="file" name="image" accept="image/*" class="w-full border rounded-lg p-2 mb-6">

        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg">Add Product</button>
        <a href="{{ route('vendor.products.index') }}" class="ml-2 bg-gray-200 px-6 py-2 rounded-lg inline-block">Cancel</a>
    </form>
</div>
@endsection