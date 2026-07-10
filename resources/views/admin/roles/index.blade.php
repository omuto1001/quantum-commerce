{{-- resources/views/admin/roles/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Roles & Permissions</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    {{-- One card per role, each with its own permission checklist and save button --}}
    @foreach ($roles as $role)
        <div class="border rounded-lg p-4 mb-4">
            <h3 class="font-bold capitalize mb-3">{{ $role->name }} Role</h3>

            <form method="POST" action="{{ route('admin.roles.permissions.update', $role) }}">
                @csrf
                @method('PUT')

                @php
    // Group permissions into readable sections for display purposes only.
    // This grouping doesn't affect how they're stored - just how they look here.
    $groups = [
        'Account' => ['view own profile', 'edit own profile'],
        'Customer' => ['browse products', 'manage own cart', 'place orders', 'view own orders', 'cancel own orders', 'write reviews'],
        'Vendor' => ['manage own shop', 'manage own products', 'view own vendor orders', 'update own order status', 'view own wallet', 'request payout'],
        'Rider' => ['view own deliveries', 'update delivery status', 'toggle own availability', 'view own delivery earnings'],
        'Admin / Platform-wide' => ['manage vendors', 'manage riders', 'manage customers', 'approve applications', 'manage products', 'manage orders', 'view deliveries', 'manage categories', 'manage payouts', 'view reports', 'manage roles'],
    ];
@endphp

<div class="mb-4 space-y-4">
    @foreach ($groups as $groupName => $permissionNames)
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">{{ $groupName }}</p>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach ($allPermissions->whereIn('name', $permissionNames) as $permission)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                            {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}>
                        {{ ucfirst($permission->name) }}
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

                <button type="submit" class="bg-green-700 text-white px-4 py-1.5 rounded text-sm">
                    Save Permissions
                </button>
            </form>
        </div>
    @endforeach

    <a href="{{ route('admin.roles.users') }}" class="inline-block mt-2 text-green-700 underline text-sm">
        Manage individual user roles →
    </a>
</div>
@endsection