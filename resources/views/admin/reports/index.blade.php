{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Reports & Analytics</h1>
    <p class="text-gray-500 mt-1">Platform-wide performance overview.</p>
</div>

{{-- Top-level summary cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500 font-medium">Total Revenue (GMV)</p>
        <p class="text-2xl font-bold mt-2 text-gray-800">UGX {{ number_format($totalRevenue, 2) }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500 font-medium">Total Orders</p>
        <p class="text-2xl font-bold mt-2 text-gray-800">{{ $totalOrders }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500 font-medium">Platform Commission</p>
        <p class="text-2xl font-bold mt-2 text-green-700">UGX {{ number_format($totalCommission, 2) }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500 font-medium">Delivered Items</p>
        <p class="text-2xl font-bold mt-2 text-gray-800">{{ $deliveredItems }}</p>
    </div>
</div>

{{-- Revenue trend line chart --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Revenue - Last 7 Days</h2>
    <div id="revenueChart" style="width: 100%; height: 300px;"></div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

    {{-- Top vendors pie chart --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Top Vendors by Sales</h2>
        <div id="vendorsChart" style="width: 100%; height: 320px;"></div>
    </div>

    {{-- Top products bar chart --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Top Products by Units Sold</h2>
        <div id="productsChart" style="width: 100%; height: 320px;"></div>
    </div>
</div>

{{-- Data tables underneath, for exact figures --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
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

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
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

{{-- Highcharts rendering script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Revenue trend line chart ----
    Highcharts.chart('revenueChart', {
        chart: { type: 'areaspline' },
        title: { text: null },
        credits: { enabled: false },
        xAxis: {
            categories: @json($last7Days->pluck('date')),
        },
        yAxis: {
            title: { text: 'Revenue (UGX)' },
        },
        series: [{
            name: 'Revenue',
            data: @json($last7Days->pluck('total')),
            color: '#15803d',
            fillOpacity: 0.15,
        }],
        plotOptions: {
            areaspline: { marker: { enabled: true } }
        }
    });

    // ---- Top vendors pie chart ----
    Highcharts.chart('vendorsChart', {
        chart: { type: 'pie' },
        title: { text: null },
        credits: { enabled: false },
        tooltip: {
            pointFormat: '<b>{point.percentage:.1f}%</b> (UGX {point.y:,.0f})'
        },
        series: [{
            name: 'Sales',
            colorByPoint: true,
            data: [
                @foreach ($topVendors as $entry)
                    {
                        name: @json($entry->vendor->shop_name ?? 'Unknown'),
                        y: {{ (float) $entry->total_sales }}
                    },
                @endforeach
            ]
        }]
    });

    // ---- Top products bar chart ----
    Highcharts.chart('productsChart', {
        chart: { type: 'bar' },
        title: { text: null },
        credits: { enabled: false },
        xAxis: {
            categories: [
                @foreach ($topProducts as $entry)
                    @json($entry->product->name ?? 'Unknown'),
                @endforeach
            ],
        },
        yAxis: {
            title: { text: 'Units Sold' },
        },
        series: [{
            name: 'Units Sold',
            data: [
                @foreach ($topProducts as $entry)
                    {{ (int) $entry->total_sold }},
                @endforeach
            ],
            color: '#ea580c',
        }],
        legend: { enabled: false },
    });
});
</script>

@endsection