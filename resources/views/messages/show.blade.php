@extends('layouts.app')

@section('content')

<div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40 p-6 max-w-2xl mx-auto">
    <h1 class="text-xl font-bold text-gray-800 mb-1">
        {{ auth()->user()->isVendor() ? $order->user->name : $vendor->shop_name }}
    </h1>
    <p class="text-xs text-gray-500 mb-4">Order #{{ $order->id }}</p>

    <div class="flex flex-col gap-3 mb-6 max-h-96 overflow-y-auto">
        @forelse ($messages as $message)
            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[75%] px-4 py-2 rounded-2xl text-sm {{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-800' }}">
                    {{ $message->body }}
                    <p class="text-[10px] mt-1 opacity-70">{{ $message->created_at->format('g:i A, M j') }}</p>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500">No messages yet. Say hello!</p>
        @endforelse
    </div>

    <form method="POST" action="{{ route('messages.store', [$order, $vendor]) }}" class="flex gap-2">
        @csrf
        <input type="text" name="body" placeholder="Type a message..." class="flex-1 border rounded-lg p-2 text-sm" required>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Send</button>
    </form>
</div>

@endsection