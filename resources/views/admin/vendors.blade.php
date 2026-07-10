{{-- resources/views/admin/vendors.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-2xl font-bold mb-6">All Vendors</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b">
                <th class="py-2">Name</th>
                <th>Shop Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vendors as $vendor)
                <tr class="border-b">
                    <td class="py-2">{{ $vendor->name }}</td>
                    <td>{{ $vendor->vendor->shop_name ?? 'N/A' }}</td>
                    <td>
                        {{-- Color-coded status badge --}}
                        @if ($vendor->approval_status === 'approved')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Approved</span>
                        @elseif ($vendor->approval_status === 'pending')
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Pending</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Rejected</span>
                        @endif
                    </td>
                    <td class="flex gap-2 py-2">
                        <a href="{{ route('admin.users.show', $vendor) }}" class="bg-gray-700 text-white px-3 py-1 rounded text-sm">View</a>

                        {{-- Delete requires confirmation via JS confirm() before submitting --}}
                        <form method="POST" action="{{ route('admin.users.destroy', $vendor) }}"
                              onsubmit="return confirm('Are you sure you want to delete this vendor? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-4 text-center text-gray-500">No vendors registered yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection