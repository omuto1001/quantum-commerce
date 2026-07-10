{{-- resources/views/reviews/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/402xl shadow-sm border border-gray-100 p-8 max-w-xl">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Rate this Product</h2>
    <p class="text-gray-500 mb-6">{{ $orderItem->product->name }} from {{ $orderItem->vendor->shop_name }}</p>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('reviews.store', $orderItem) }}">
        @csrf

        <label class="block font-semibold mb-2">Your Rating</label>
        {{-- Simple 1-5 star radio buttons --}}
        <div class="flex gap-4 mb-6">
            @for ($i = 1; $i <= 5; $i++)
                <label class="flex flex-col items-center cursor-pointer">
                    <input type="radio" name="rating" value="{{ $i }}" class="mb-1" {{ old('rating') == $i ? 'checked' : '' }} required>
                    <span class="text-2xl">⭐</span>
                    <span class="text-xs text-gray-500">{{ $i }}</span>
                </label>
            @endfor
        </div>

        <label class="block font-semibold mb-1">Comment (optional)</label>
        <textarea name="comment" rows="4" class="w-full border rounded-lg p-2 mb-6" placeholder="Share your experience with this product...">{{ old('comment') }}</textarea>

        <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-6 py-2 rounded-lg font-medium">
            Submit Review
        </button>
        <a href="{{ route('orders.show', $orderItem->order_id) }}" class="ml-2 bg-gray-200 px-6 py-2 rounded-lg inline-block">Cancel</a>
    </form>
</div>
@endsection