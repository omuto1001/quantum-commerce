{{-- resources/views/auth/register-vendor.blade.php --}}
<x-guest-layout>
    <h2 class="text-2xl font-bold mb-2">Register as a Vendor</h2>
    <p class="text-sm text-gray-600 mb-6">Your account needs admin approval before you can start selling.</p>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.vendor') }}">
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

        <h3 class="font-bold mt-6 mb-2">Shop Details</h3>
        <label class="block font-semibold mb-1">Shop Name</label>
        <input type="text" name="shop_name" value="{{ old('shop_name') }}" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Shop Description</label>
        <textarea name="shop_description" class="w-full border rounded p-2 mb-6">{{ old('shop_description') }}</textarea>

        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded">Submit Application</button>
    </form>

    <div class="mt-6 text-sm">
        Already have an account? <a href="{{ route('login') }}" class="text-green-700 underline">Login</a>
    </div>
</x-guest-layout>