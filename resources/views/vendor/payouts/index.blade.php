{{-- resources/views/vendor/payouts/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">My Payouts</h1>

    {{-- Wallet balance + request form --}}
    <div class="bg-white rbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40ounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <p class="text-sm text-gray-500">Available Wallet Balance</p>
        <p class="text-3xl font-bold text-green-700 mt-1">UGX {{ number_format($walletBalance, 2) }}</p>

        @if ($walletBalance > 0)
            <form method="POST" action="{{ route('vendor.payouts.store') }}" class="mt-4 flex gap-2">
                @csrf
                <input type="number" step="0.01" name="amount" value="{{ old('amount', $walletBalance) }}"
                       max="{{ $walletBalance }}" class="flex-1 border rounded-lg p-2" required>
                <button class="bg-green-700 text-white px-6 py-2 rounded-lg text-sm">Request Payout</button>
            </form>
            @error('amount') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        @else
            <p class="text-sm text-gray-400 mt-3">No balance available for payout yet.</p>
        @endif
    </div>

    {{-- Payout history --}}
    <div class="bg-white rounded-2xl shadow-sm bordebg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40r border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Payout History</h2>

        @forelse ($payouts as $payout)
            <div class="flex items-center justify-between p-3 border-b last:border-b-0">
                <div>
                    <p class="font-semibold text-gray-800">UGX {{ number_format($payout->amount, 2) }}</p>
                    <p class="text-xs text-gray-500">{{ $payout->created_at->format('d M Y') }}</p>
                    @if ($payout->admin_note)
                        <p class="text-xs text-red-600 mt-1">Note: {{ $payout->admin_note }}</p>
                    @endif
                </div>
                <span class="text-xs px-2 py-1 rounded-full
                      @if ($payout->status === 'paid') bg-green-100 text-green-700
                      @elseif ($payout->status === 'approved') bg-blue-100 text-blue-700
                      @elseif ($payout->status === 'rejected') bg-red-100 text-red-700
                      @else bg-yellow-100 text-yellow-700
                      @endif">
                    {{ ucfirst($payout->status) }}
                </span>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No payout requests yet.</p>
        @endforelse
    </div>
</div>
@endsection