{{-- resources/views/vendor/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')

{{-- Welcome header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }} 👋</h1>
    <p class="text-gray-500 mt-1">Here's how {{ $vendor->shop_name }} is doing.</p>
</div>

{{-- Stat cards row --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

    {{-- Products count - real data from the Products module --}}
<div class="bg-whitebg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40 rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 font-medium">My Products</p>
            <p class="text-3xl font-bold mt-2 text-gray-800">{{ $productCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-2xl">
            🏷️
        </div>
    </div>
    <a href="{{ url('/vendor/products') }}" class="text-xs text-amber-700 underline mt-3 inline-block">Add your first product →</a>
</div>

{{-- Orders count - placeholder until Orders module exists --}}
<div class="bg-white robg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40unded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 font-medium">Orders Received</p>
            <p class="text-3xl font-bold mt-2 text-gray-800">{{ $orderCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-2xl">
            📦
        </div>
    </div>
    <a href="{{ url('/vendor/orders') }}" class="text-xs text-blue-700 underline mt-3 inline-block">View orders →</a>
</div>

    {{-- Wallet balance - real data from the vendor's profile --}}
    <div class=" p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Wallet Balance</p>
                <p class="text-2xl font-bold mt-2 text-green-700">UGX {{ number_format($vendor->wallet_balance, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-2xl">
                💰
            </div>
        </div>
    </div>

    {{-- Commission rate - real data --}}
    <div class="bg-white roundbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40ed-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Commission Rate</p>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $vendor->commission_rate }}%</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-2xl">
                📊
            </div>
        </div>
    </div>
</div>

{{-- Shop info card --}}
<div class="bg-white rounbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40ded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">🏪 Shop Overview</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <p class="text-xs text-gray-400 uppercase font-medium">Shop Name</p>
            <p class="text-gray-800 font-medium mt-1">{{ $vendor->shop_name }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 uppercase font-medium">Description</p>
            <p class="text-gray-800 mt-1">{{ $vendor->shop_description ?: 'No description added yet' }}</p>
        </div>
    </div>
    <a href="{{ route('profile.edit') }}" class="text-sm text-green-700 underline mt-4 inline-block">Edit shop details →</a>
</div>

{{-- Coming soon notice --}}
<div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
    <p class="text-blue-800 font-medium">🚧 More coming soon</p>
    <p class="text-blue-700 text-sm mt-1">Product management and order tracking will appear here once those features are built.</p>
</div>

@endsection