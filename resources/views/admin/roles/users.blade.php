{{-- resources/views/admin/roles/users.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Manage User Roles</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b">
                <th class="py-2">Name</th>
                <th>Email</th>
                <th>Current Role</th>
                <th>Change Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-b">
                    <td class="py-2">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roleLabel() }}</td>
                    <td>
                        {{-- Each row has its own small form to change just that user's role --}}
                        <form method="POST" action="{{ route('admin.roles.users.update', $user) }}" class="flex gap-2">
                            @csrf
                            @method('PUT')

                            <select name="role" class="border rounded p-1 text-sm">
                                @foreach (['customer', 'vendor', 'rider', 'admin'] as $roleOption)
                                    <option value="{{ $roleOption }}" {{ $user->hasRole($roleOption) ? 'selected' : '' }}>
                                        {{ ucfirst($roleOption) }}
                                    </option>
                                @endforeach
                            </select>

                            <button class="bg-green-700 text-white px-3 py-1 rounded text-sm">Update</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection