{{-- resources/views/admin/approvals.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Pending Vendor / Rider Approvals</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b">
                <th class="py-2">Name</th>
                <th>Role</th>
                <th>Details</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            {{-- Loop through every pending vendor/rider --}}
            @forelse ($pendingUsers as $pendingUser)
                <tr class="border-b">
                    <td class="py-2">{{ $pendingUser->name }}</td>
                    <td>{{ $pendingUser->roleLabel() }}</td>
                    <td>
                        {{-- Show extra role-specific info to help the admin decide --}}
                        @if ($pendingUser->isVendor() && $pendingUser->vendor)
                            Shop: {{ $pendingUser->vendor->shop_name }}
                        @elseif ($pendingUser->isRider() && $pendingUser->rider)
                            Vehicle: {{ $pendingUser->rider->vehicle_type }}
                        @endif
                    </td>
                    <td class="flex gap-2 py-2">
    {{-- Lets the admin review full details before deciding --}}
    <a href="{{ route('admin.users.show', $pendingUser) }}" class="bg-gray-700 text-white px-3 py-1 rounded">
        View
    </a>

    <form method="POST" action="{{ route('admin.approvals.approve', $pendingUser) }}">
        @csrf
        <button class="bg-green-700 text-white px-3 py-1 rounded">Approve</button>
    </form>
    <form method="POST" action="{{ route('admin.approvals.reject', $pendingUser) }}">
        @csrf
        <button class="bg-red-600 text-white px-3 py-1 rounded">Reject</button>
    </form>
</td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-4 text-center text-gray-500">No pending applications.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection