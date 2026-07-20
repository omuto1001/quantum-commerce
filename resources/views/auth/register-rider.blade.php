{{-- resources/views/auth/register-rider.blade.php --}}
<x-guest-layout>
    <h2 class="text-2xl font-bold mb-2">Register as a Delivery Agent</h2>
    <p class="text-sm text-gray-600 mb-6">Your account needs admin approval before you can accept deliveries.</p>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.rider') }}">
        @csrf

        <h3 class="font-bold mt-4 mb-2">Personal Details</h3>
        <label class="block font-semibold mb-1">Full Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. matt@gmail.com" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Phone Number</label>
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 0700000000" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Address</label>
        <input type="text" name="address" value="{{ old('address') }}" placeholder="e.g. 123 Main St, City" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Password</label>
        <input type="password" name="password" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Confirm Password</label>
        <input type="password" name="password_confirmation" class="w-full border rounded p-2 mb-4" required>

        <h3 class="font-bold mt-6 mb-2">Delivery Details</h3>
        <label class="block font-semibold mb-1">Vehicle Type</label>
        <select name="vehicle_type" class="w-full border rounded p-2 mb-4" required>
            <option value="">-- Select --</option>
            <option value="Motorcycle" {{ old('vehicle_type') == 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
            <option value="Bicycle" {{ old('vehicle_type') == 'Bicycle' ? 'selected' : '' }}>Bicycle</option>
            <option value="Car" {{ old('vehicle_type') == 'Car' ? 'selected' : '' }}>Car</option>
        </select>

        <label class="block font-semibold mb-1">License Plate (if applicable)</label>
        <input type="text" name="license_plate" value="{{ old('license_plate') }}" class="w-full border rounded p-2 mb-4">

        <label class="block font-semibold mb-1">National ID Number</label>
        <input type="text" name="national_id_number" value="{{ old('national_id_number') }}" class="w-full border rounded p-2 mb-6">

        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded">Submit Application</button>
    </form>

    <div class="mt-6 text-sm">
        Already have an account? <a href="{{ route('login') }}" class="text-green-700 underline">Login</a>
    </div>
</x-guest-layout>