{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-8 max-w-2xl">
    <h2 class="text-2xl font-bold mb-6">Edit Profile</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT') {{-- profile.update route expects a PUT request --}}

        <label class="block font-semibold mb-1">Full Name</label>
        {{-- old('name', $user->name) means: use the old input if validation
             just failed, otherwise fall back to the user's saved value.
             This is what makes the form pre-filled instead of empty. --}}
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Email Address</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Phone Number</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Address</label>
        <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full border rounded p-2 mb-4" required>

        {{-- Vendor-only fields --}}
        @if ($user->isVendor() && $user->vendor)
            <label class="block font-semibold mb-1">Shop Name</label>
            <input type="text" name="shop_name" value="{{ old('shop_name', $user->vendor->shop_name) }}" class="w-full border rounded p-2 mb-4" required>

            <label class="block font-semibold mb-1">Shop Description</label>
            <textarea name="shop_description" class="w-full border rounded p-2 mb-4">{{ old('shop_description', $user->vendor->shop_description) }}</textarea>
        @endif

        {{-- Rider-only fields --}}
        @if ($user->isRider() && $user->rider)
            <label class="block font-semibold mb-1">Vehicle Type</label>
            <select name="vehicle_type" class="w-full border rounded p-2 mb-4" required>
                @foreach (['Motorcycle', 'Bicycle', 'Car'] as $type)
                    <option value="{{ $type }}" {{ old('vehicle_type', $user->rider->vehicle_type) == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select>

            <label class="block font-semibold mb-1">License Plate</label>
            <input type="text" name="license_plate" value="{{ old('license_plate', $user->rider->license_plate) }}" class="w-full border rounded p-2 mb-4">

            <label class="flex items-center gap-2 mb-4">
    <input type="checkbox" name="is_available" value="1"
           {{ old('is_available', $user->rider->is_available) ? 'checked' : '' }}
           class="rounded border-gray-300">
    <span class="font-semibold">I'm available to accept deliveries</span>
</label>
        @endif

        <label class="block font-semibold mb-1">New Password (leave blank to keep current password)</label>
        <input type="password" name="password" class="w-full border rounded p-2 mb-4">

        <label class="block font-semibold mb-1">Confirm New Password</label>
        <input type="password" name="password_confirmation" class="w-full border rounded p-2 mb-6">

        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded">Save Changes</button>
        <a href="{{ route('profile.show') }}" class="ml-2 bg-gray-200 px-6 py-2 rounded inline-block">Cancel</a>
    </form>
</div>
@endsection