{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">All Users</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto -mx-6 px-6">
        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="border-b text-sm text-gray-500">
                    <th class="py-2">Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b">
                        <td class="py-3 font-medium text-gray-800">{{ $user->name }}</td>
                        <td class="text-sm">{{ $user->email }}</td>
                        <td>
                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">
                                {{ $user->roleLabel() }}
                            </span>
                        </td>
                        <td>
                            @if ($user->approval_status === 'approved')
                                <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">Approved</span>
                            @elseif ($user->approval_status === 'pending')
                                <span class="text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                            @else
                                <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">Rejected</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="text-blue-700 text-sm underline">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection