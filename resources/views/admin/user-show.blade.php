{{-- resources/views/admin/user-show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-8 max-w-2xl">
    <h2 class="text-2xl font-bold mb-6">{{ $user->roleLabel() }} Details</h2>

    <div class="mb-4">
        <p class="text-sm text-gray-500">Full Name</p>
        <p class="text-lg font-semibold">{{ $user->name }}</p>
    </div>

    <div class="mb-4">
        <p class="text-sm text-gray-500">Email</p>
        <p class="text-lg font-semibold">{{ $user->email }}</p>
    </div>

    <div class="mb-4">
        <p class="text-sm text-gray-500">Phone</p>
        <p class="text-lg font-semibold">{{ $user->phone }}</p>
    </div>

    <div class="mb-4">
        <p class="text-sm text-gray-500">Address</p>
        <p class="text-lg font-semibold">{{ $user->address }}</p>
    </div>

    <div class="mb-4">
        <p class="text-sm text-gray-500">Approval Status</p>
        <p class="text-lg font-semibold capitalize">{{ $user->approval_status }}</p>
    </div>

    @if ($user->isVendor() && $user->vendor)
        <hr class="my-4">
        <h3 class="font-bold mb-2">Shop Details</h3>
        <div class="mb-4">
            <p class="text-sm text-gray-500">Shop Name</p>
            <p class="text-lg font-semibold">{{ $user->vendor->shop_name }}</p>
        </div>
        <div class="mb-4">
            <p class="text-sm text-gray-500">Shop Description</p>
            <p>{{ $user->vendor->shop_description ?? 'Not set' }}</p>
        </div>
        <div class="mb-4">
            <p class="text-sm text-gray-500">Commission Rate</p>
            <p class="text-lg font-semibold">{{ $user->vendor->commission_rate }}%</p>
        </div>
        <div class="mb-4">
            <p class="text-sm text-gray-500">Wallet Balance</p>
            <p class="text-lg font-semibold">UGX {{ number_format($user->vendor->wallet_balance, 2) }}</p>
        </div>
    @endif

    @if ($user->isRider() && $user->rider)
        <hr class="my-4">
        <h3 class="font-bold mb-2">Delivery Details</h3>
        <div class="mb-4">
            <p class="text-sm text-gray-500">Vehicle Type</p>
            <p class="text-lg font-semibold">{{ $user->rider->vehicle_type }}</p>
        </div>
        <div class="mb-4">
            <p class="text-sm text-gray-500">License Plate</p>
            <p>{{ $user->rider->license_plate ?? 'Not set' }}</p>
        </div>
        <div class="mb-4">
            <p class="text-sm text-gray-500">National ID Number</p>
            <p>{{ $user->rider->national_id_number ?? 'Not set' }}</p>
        </div>
    @endif

    <div class="mt-6 flex gap-3 flex-wrap">
    {{-- Approve/Reject only make sense while the account is still pending --}}
    @if ($user->isPending())
        <form method="POST" action="{{ route('admin.approvals.approve', $user) }}">
            @csrf
            <button class="bg-green-700 text-white px-6 py-2 rounded">Approve</button>
        </form>

        <form method="POST" action="{{ route('admin.approvals.reject', $user) }}">
            @csrf
            <button class="bg-red-600 text-white px-6 py-2 rounded">Reject</button>
        </form>
    @endif

    @if ($user->isVendor())
        <a href="{{ route('admin.vendors') }}" class="bg-gray-200 px-6 py-2 rounded">Back to Vendors</a>
    @elseif ($user->isRider())
        <a href="{{ route('admin.riders') }}" class="bg-gray-200 px-6 py-2 rounded">Back to Riders</a>
    @endif

    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
          onsubmit="return confirm('Are you sure you want to delete this account? This cannot be undone.');">
        @csrf
        @method('DELETE')
        <button class="bg-red-600 text-white px-6 py-2 rounded">Delete Account</button>
    </form>
</div>
</div>
@endsection