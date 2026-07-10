{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-2bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Manage Categories</h2>

    {{-- Add new category form --}}
    <form method="POST" action="{{ route('admin.categories.store') }}" class="flex gap-2 mb-6">
        @csrf
        <input type="text" name="name" placeholder="New category name" class="flex-1 border rounded-lg p-2" required>
        <button class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm">Add</button>
    </form>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-sm text-gray-500">
                <th class="py-2">Name</th>
                <th>Products</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr class="border-b">
                    <td class="py-3">
                        {{-- Inline edit form for the name --}}
                        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex gap-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $category->name }}" class="border rounded p-1 text-sm">
                            <button class="text-xs bg-gray-700 text-white px-2 py-1 rounded">Save</button>
                        </form>
                    </td>
                    <td>{{ $category->products_count }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              onsubmit="return confirm('Delete this category? Products in it will become uncategorized.');">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 text-sm hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="py-4 text-center text-gray-500">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection