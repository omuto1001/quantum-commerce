{{-- resources/views/profile/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-3xl">

    {{-- Profile header card with avatar and status badge --}}
    <div class="bg-white robg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40unded-2xl shadow-sm border border-gray-100 p-8 mb-6">
        <div class="flex items-center gap-5">
            {{-- Large avatar circle with the user's initial --}}
            <div class="w-20 h-20 rounded-full bg-green-700 text-white flex items-center justify-center text-3xl font-bold shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
                <p class="text-gray-500">{{ $user->email }}</p>

                <div class="flex gap-2 mt-2">
                    {{-- Role badge --}}
                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $user->roleLabel() }}
                    </span>

                    {{-- Approval status badge, color-coded --}}
                    @if ($user->approval_status === 'approved')
                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">Approved</span>
                    @elseif ($user->approval_status === 'pending')
                        <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">Pending Approval</span>
                    @else
                        <span class="bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">Rejected</span>
                    @endif
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-lg text-sm font-medium shrink-0">
                Edit Profile
            </a>
        </div>
    </div>

    {{-- Personal details card --}}
    <div class="bg-white rounded-bg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/402xl shadow-sm border border-gray-100 p-8 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Personal Details</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-gray-400 uppercase font-medium">Phone Number</p>
                <p class="text-gray-800 font-medium mt-1">{{ $user->phone ?: 'Not set' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-medium">Address</p>
                <p class="text-gray-800 font-medium mt-1">{{ $user->address ?: 'Not set' }}</p>
            </div>
        </div>
    </div>

    {{-- Shop details card - vendors only --}}
    @if ($user->isVendor() && $user->vendor)
        <div class="bg-whitebg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40 rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">🏷️ Shop Details</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Shop Name</p>
                    <p class="text-gray-800 font-medium mt-1">{{ $user->vendor->shop_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Commission Rate</p>
                    <p class="text-gray-800 font-medium mt-1">{{ $user->vendor->commission_rate }}%</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-xs text-gray-400 uppercase font-medium">Shop Description</p>
                    <p class="text-gray-800 mt-1">{{ $user->vendor->shop_description ?: 'No description added yet' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Wallet Balance</p>
                    <p class="text-green-700 font-bold text-lg mt-1">UGX {{ number_format($user->vendor->wallet_balance, 2) }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Delivery details card - riders only --}}
    @if ($user->isRider() && $user->rider)
        <div class="bg-white rounded-2xlbg-white/70 backdrop-blur-md rounded-2xl shadow-lg border border-white/40 shadow-sm border border-gray-100 p-8 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">🏍️ Delivery Details</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Vehicle Type</p>
                    <p class="text-gray-800 font-medium mt-1">{{ $user->rider->vehicle_type }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">License Plate</p>
                    <p class="text-gray-800 font-medium mt-1">{{ $user->rider->license_plate ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">National ID Number</p>
                    <p class="text-gray-800 font-medium mt-1">{{ $user->rider->national_id_number ?: 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Availability</p>
                    <p class="mt-1">
                        @if ($user->rider->is_available)
                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">Available</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-3 py-1 rounded-full">Unavailable</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection