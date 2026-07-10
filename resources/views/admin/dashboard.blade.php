{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')

{{-- Welcome header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }} 👋</h1>
    <p class="text-gray-500 mt-1">Here's what's happening on Quantum Commerce today.</p>
</div>

{{-- Stat cards row --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

    {{-- Total customers card --}}
    <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40 p-6 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Customers</p>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $totalCustomers }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-2xl">
                🛍️
            </div>
        </div>
    </div>

    {{-- Total vendors card --}}
    <div class="bg-white rounded-2xlbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40 shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Vendors</p>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $totalVendors }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-2xl">
                🏷️
            </div>
        </div>
        <a href="{{ route('admin.vendors') }}" class="text-xs text-amber-700 underline mt-3 inline-block">Manage vendors →</a>
    </div>

    {{-- Total riders card --}}
    <div class="bg-whibg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40te rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Delivery Agents</p>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $totalRiders }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-2xl">
                🏍️
            </div>
        </div>
        <a href="{{ route('admin.riders') }}" class="text-xs text-purple-700 underline mt-3 inline-block">Manage riders →</a>
    </div>

    {{-- Pending approvals card - stands out with color when count > 0 --}}
    <div class="rounded-2xl shadow-sm border p-6 transition
                {{ $pendingCount > 0 ? 'bg-yellow-50 border-yellow-300' : 'bg-white border-gray-100' }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium {{ $pendingCount > 0 ? 'text-yellow-700' : 'text-gray-500' }}">Pending Approvals</p>
                <p class="text-3xl font-bold mt-2 {{ $pendingCount > 0 ? 'text-yellow-800' : 'text-gray-800' }}">{{ $pendingCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl
                        {{ $pendingCount > 0 ? 'bg-yellow-200' : 'bg-gray-100' }}">
                ⏳
            </div>
        </div>
        <a href="{{ route('admin.approvals') }}" class="text-xs underline mt-3 inline-block
                  {{ $pendingCount > 0 ? 'text-yellow-800' : 'text-gray-500' }}">
            Review applications →
        </a>
    </div>
</div>
{{-- Platform revenue card - the actual commission earned so far --}}
<div class="bg-white roundebg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40d-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 font-medium">Total Platform Revenue (Commission)</p>
            <p class="text-3xl font-bold mt-2 text-green-700">UGX {{ number_format($platformRevenue, 2) }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-2xl">
            🏦
        </div>
    </div>
</div>


{{-- Quick actions row --}}
<div class="bg-whibg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40te rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <a href="{{ route('admin.approvals') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
            <span class="text-2xl">✅</span>
            <span class="text-sm font-medium text-gray-700 text-center">Approvals</span>
        </a>

        <a href="{{ route('admin.vendors') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
            <span class="text-2xl">🏷️</span>
            <span class="text-sm font-medium text-gray-700 text-center">Vendors</span>
        </a>

        <a href="{{ route('admin.riders') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
            <span class="text-2xl">🏍️</span>
            <span class="text-sm font-medium text-gray-700 text-center">Riders</span>
        </a>

        <a href="{{ route('admin.roles.index') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
            <span class="text-2xl">🔐</span>
            <span class="text-sm font-medium text-gray-700 text-center">Roles</span>
        </a>
    </div>
</div>

@endsection