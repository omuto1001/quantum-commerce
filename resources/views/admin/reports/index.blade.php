{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Reports & Analytics</h1>
    <p class="text-gray-500 mt-1">Platform-wide performance overview.</p>
</div>

{{-- Top-level summary cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-2bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500 font-medium">Total Revenue (GMV)</p>
        <p class="text-2xl font-bold mt-2 text-gray-800">UGX {{ number_format($totalRevenue, 2) }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border bordbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40er-gray-100 p-6">
        <p class="text-sm text-gray-500 font-medium">Total Orders</p>
        <p class="text-2xl font-bold mt-2 text-gray-800">{{ $totalOrders }}</p>
    </div>
    <div class="bg-white rbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40ounded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500 font-medium">Platform Commission</p>
        <p class="text-2xl font-bold mt-2 text-green-700">UGX {{ number_format($totalCommission, 2) }}</p>
    </div>
    <div class="bg-white roubg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40nded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500 font-medium">Delivered Items</p>
        <p class="text-2xl font-bold mt-2 text-gray-800">{{ $deliveredItems }}</p>
    </div>
</div>

{{-- Simple 7-day revenue bar chart using plain HTML/CSS bars --}}
<div class="bg-white rounded-2xl shadow-sm border border-bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40gray-100 p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Revenue - Last 7 Days</h2>

    @php
        $maxValue = $last7Days->max('total') ?: 1; // avoid divide-by-zero if all days are 0
    @endphp

    <div class="flex items-end gap-4 h-40">
        @foreach ($last7Days as $day)
            <div class="flex-1 flex flex-col items-center justify-end h-full">
                <div class="w-full bg-green-600 rounded-t-lg" style="height: {{ ($day['total'] / $maxValue) * 100 }}%; min-height: 2px;"></div>
                <p class="text-xs text-gray-500 mt-2">{{ $day['date'] }}</p>
            </div>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Top vendors table --}}
    <div class="bg-white rounded-2xl shadow-sm borbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40der border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Top Vendors</h2>
        @forelse ($topVendors as $entry)
            <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                <div>
                    <p class="font-medium text-gray-800">{{ $entry->vendor->shop_name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-500">{{ $entry->items_sold }} items sold</p>
                </div>
                <p class="font-semibold text-green-700">UGX {{ number_format($entry->total_sales, 2) }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No completed sales yet.</p>
        @endforelse
    </div>

    {{-- Top products table --}}
    <div class="bg-white rounded-2xbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40l shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Top Products</h2>
        @forelse ($topProducts as $entry)
            <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                <div>
                    <p class="font-medium text-gray-800">{{ $entry->product->name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-500">{{ $entry->total_sold }} units sold</p>
                </div>
                <p class="font-semibold text-green-700">UGX {{ number_format($entry->total_revenue, 2) }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No completed sales yet.</p>
        @endforelse
    </div>
</div>

@endsection