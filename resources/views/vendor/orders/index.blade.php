{{-- resources/views/vendor/orders/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40 p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Orders Received</h2>

    <div class="overflow-x-auto -mx-6 px-6">
        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="border-b text-sm text-gray-500">
                    <th class="py-2">Order #</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Qty</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orderItems as $item)
                    <tr class="border-b">
                        <td class="py-3">#{{ $item->order_id }}</td>
                        <td class="font-medium text-gray-800">{{ $item->product->name }}</td>
                        <td>{{ $item->order->user->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>UGX {{ number_format($item->price * $item->quantity, 2) }}</td>
                        <td>
                            <span class="text-xs px-2 py-1 rounded-full
                                  @if ($item->status === 'delivered') bg-green-100 text-green-700
                                  @elseif ($item->status === 'shipped') bg-blue-100 text-blue-700
                                  @elseif ($item->status === 'confirmed') bg-purple-100 text-purple-700
                                  @elseif ($item->status === 'cancelled') bg-gray-200 text-gray-600
                                  @else bg-yellow-100 text-yellow-700
                                  @endif">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            @if ($item->status !== 'cancelled')
                                <form method="POST" action="{{ route('vendor.orders.update', $item) }}" class="flex gap-1">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="border rounded p-1 text-xs">
                                        @foreach (['pending', 'confirmed', 'shipped', 'delivered'] as $statusOption)
                                            <option value="{{ $statusOption }}" {{ $item->status === $statusOption ? 'selected' : '' }}>
                                                {{ ucfirst($statusOption) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="bg-green-700 text-white px-2 py-1 rounded text-xs">Save</button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">Cancelled</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-6 text-center text-gray-500">No orders received yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection