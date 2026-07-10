{{-- resources/views/admin/payouts/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-whbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40ite rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Payout Requests</h2>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-sm text-gray-500">
                <th class="py-2">Vendor</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payouts as $payout)
                <tr class="border-b">
                    <td class="py-3">{{ $payout->vendor->shop_name }}</td>
                    <td>UGX {{ number_format($payout->amount, 2) }}</td>
                    <td>{{ $payout->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="text-xs px-2 py-1 rounded-full
                              @if ($payout->status === 'paid') bg-green-100 text-green-700
                              @elseif ($payout->status === 'approved') bg-blue-100 text-blue-700
                              @elseif ($payout->status === 'rejected') bg-red-100 text-red-700
                              @else bg-yellow-100 text-yellow-700
                              @endif">
                            {{ ucfirst($payout->status) }}
                        </span>
                    </td>
                    <td class="flex gap-2 py-3">
                        @if ($payout->status === 'requested')
                            <form method="POST" action="{{ route('admin.payouts.approve', $payout) }}">
                                @csrf @method('PUT')
                                <button class="bg-blue-700 text-white px-3 py-1 rounded text-xs">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.payouts.reject', $payout) }}"
                                  onsubmit="return confirm('Reject this payout? Amount will be refunded to vendor wallet.');">
                                @csrf
                                <button class="bg-red-600 text-white px-3 py-1 rounded text-xs">Reject</button>
                            </form>
                        @elseif ($payout->status === 'approved')
                            <form method="POST" action="{{ route('admin.payouts.paid', $payout) }}">
                                @csrf @method('PUT')
                                <button class="bg-green-700 text-white px-3 py-1 rounded text-xs">Mark Paid</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-6 text-center text-gray-500">No payout requests yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection