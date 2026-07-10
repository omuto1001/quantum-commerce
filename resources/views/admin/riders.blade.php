{{-- resources/views/admin/riders.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-2xl font-bold mb-6">All Delivery Agents</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b">
                <th class="py-2">Name</th>
                <th>Vehicle/Motocycle</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riders as $rider)
                <tr class="border-b">
                    <td class="py-2">{{ $rider->name }}</td>
                    <td>{{ $rider->rider->vehicle_type ?? 'N/A' }}</td>
                    <td>
                        @if ($rider->approval_status === 'approved')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Approved</span>
                        @elseif ($rider->approval_status === 'pending')
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Pending</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Rejected</span>
                        @endif
                    </td>
                    <td class="flex gap-2 py-2">
                        <a href="{{ route('admin.users.show', $rider) }}" class="bg-gray-700 text-white px-3 py-1 rounded text-sm">View</a>

                        <form method="POST" action="{{ route('admin.users.destroy', $rider) }}"
                              onsubmit="return confirm('Are you sure you want to delete this rider? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-4 text-center text-gray-500">No delivery agents registered yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection