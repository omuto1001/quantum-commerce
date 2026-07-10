<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // Show the logged-in user's own profile with all saved details
    public function show()
    {
        $user = Auth::user();
        $user->load(['vendor', 'rider']); // load shop/vehicle info if relevant

        return view('profile.show', compact('user'));
    }

    // Show the edit form PRE-FILLED with the user's real data
    public function edit()
    {
        $user = Auth::user();
        $user->load(['vendor', 'rider']);

        return view('profile.edit', compact('user'));
    }

    // Handle the profile update submission
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => [
                'required', 'email',
                // ignore this user's own current email during the uniqueness check
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone'    => ['required', 'string', 'max:20'],
            'address'  => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // optional
        ]);

        $user->name    = $validated['name'];
        $user->email   = $validated['email'];
        $user->phone   = $validated['phone'];
        $user->address = $validated['address'];

        // Only touch the password if a new one was actually typed
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update vendor-specific fields if this user is a vendor
        if ($user->isVendor() && $user->vendor) {
            $vendorData = $request->validate([
                'shop_name'        => ['required', 'string', 'max:255'],
                'shop_description' => ['nullable', 'string'],
            ]);
            $user->vendor->update($vendorData);
        }

        // Update rider-specific fields if this user is a rider
        if ($user->isRider() && $user->rider) {
            $riderData = $request->validate([
                'vehicle_type'  => ['required', 'string', 'max:100'],
                'license_plate' => ['nullable', 'string', 'max:50'],
            ]);
            $user->rider->update($riderData);
        }

        return redirect()->route('profile.show')
            ->with('success', 'Your profile has been updated successfully.');
    }
}