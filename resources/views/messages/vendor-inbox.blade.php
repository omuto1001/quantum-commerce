@extends('layouts.app')

@section('content')

<div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40 p-6 max-w-2xl mx-auto">
    <h1 class="text-xl font-bold text-gray-800 mb-4">Customer Messages</h1>

    @forelse ($conversations as $lastMessage)
        @php
            $otherUser = $lastMessage->sender_id === auth()->id() ? $lastMessage->receiver : $lastMessage->sender;
        @endphp
        <a href="{{ route('messages.vendor.reply', $otherUser) }}" class="flex items-center justify-between p-4 border-b last:border-b-0 hover:bg-white/50 transition">
            <div>
                <p class="font-semibold text-gray-800">{{ $otherUser->name }}</p>
                <p class="text-xs text-gray-500 truncate max-w-xs">{{ $lastMessage->body }}</p>
            </div>
            <span class="text-xs text-gray-400">{{ $lastMessage->created_at->diffForHumans() }}</span>
        </a>
    @empty
        <p class="text-sm text-gray-500">No customer messages yet.</p>
    @endforelse
</div>

@endsection