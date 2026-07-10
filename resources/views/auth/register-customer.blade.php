{{-- resources/views/auth/register-customer.blade.php --}}
<x-guest-layout>
    <h2 class="text-2xl font-bold mb-6">Create a Customer Account</h2>

    {{-- Show all validation errors at the top --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.customer') }}">
        @csrf {{-- required security token for every POST form in Laravel --}}

        <label class="block font-semibold mb-1">Full Name</label>
        {{-- old('name') repopulates this if validation fails --}}
        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Phone Number</label>
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 0700000000" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Delivery Address</label>
        <input type="text" name="address" value="{{ old('address') }}" placeholder="e.g. Kampala, Ntinda" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Password</label>
        <input type="password" name="password" class="w-full border rounded p-2 mb-4" required>

        <label class="block font-semibold mb-1">Confirm Password</label>
        <input type="password" name="password_confirmation" class="w-full border rounded p-2 mb-6" required>

        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded">Register</button>
    </form>

    <div class="mt-6 text-sm">
        Want to sell products? <a href="{{ route('register.vendor') }}" class="text-green-700 underline">Register as a Vendor</a><br>
        Want to deliver orders? <a href="{{ route('register.rider') }}" class="text-green-700 underline">Register as a Rider</a><br>
        Already have an account? <a href="{{ route('login') }}" class="text-green-700 underline">Login</a>
    </div>
</x-guest-layout>