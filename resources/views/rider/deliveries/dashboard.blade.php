{{-- resources/views/rider/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }} 👋</h1>
    <p class="text-gray-500 mt-1">Here's your delivery activity.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    <div class="bg-white roundebg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40d-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Active Deliveries</p>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $activeDeliveries }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-2xl">🏍️</div>
        </div>
        <a href="{{ route('rider.deliveries.index') }}" class="text-xs text-blue-700 underline mt-3 inline-block">View deliveries →</a>
    </div>

    <div class="bg-white bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Completed Deliveries</p>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $completedDeliveries }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-2xl">✅</div>
        </div>
        <a href="{{ route('rider.history') }}" class="text-xs text-green-700 underline mt-3 inline-block">View history →</a>
    </div>

    <div class="bg-white roundbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40ed-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Vehicle</p>
                <p class="text-xl font-bold mt-2 text-gray-800">{{ $rider->vehicle_type }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-2xl">🚗</div>
        </div>
    </div>
</div>

<div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
    <p class="text-blue-800 font-medium">📍 Ready to deliver?</p>
    <p class="text-blue-700 text-sm mt-1">Check available deliveries and accept one to get started.</p>
    <a href="{{ route('rider.deliveries.index') }}" class="inline-block mt-3 bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
        View Available Deliveries
    </a>
</div>

@endsection