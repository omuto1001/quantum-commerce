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
    $oldEmail = $user->email;

    $validated = $request->validate([
        'name'    => ['required', 'string', 'max:255'],
        'email'   => [
            'required', 'email',
            Rule::unique('users', 'email')->ignore($user->id),
        ],
        'phone'    => ['required', 'string', 'max:20'],
        'address'  => ['required', 'string', 'max:255'],
        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
    ]);

    $user->name    = $validated['name'];
    $user->email   = $validated['email'];
    $user->phone   = $validated['phone'];
    $user->address = $validated['address'];

    if (! empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    $user->save();

    if ($user->isVendor() && $user->vendor) {
        $vendorData = $request->validate([
            'shop_name'        => ['required', 'string', 'max:255'],
            'shop_description' => ['nullable', 'string'],
        ]);
        $user->vendor->update($vendorData);
    }

    if ($user->isRider() && $user->rider) {
    $riderData = $request->validate([
        'vehicle_type'  => ['required', 'string', 'in:Motorcycle,Bicycle,Car'],
        'license_plate' => [
            Rule::requiredIf(in_array($request->vehicle_type, ['Motorcycle', 'Car'])),
            'nullable',
            'string',
            'regex:/^U[A-Z]{2}\s?\d{3}[A-Z]$/i',
        ],
    ], [
        'license_plate.required' => 'A number plate is required for motorcycles and cars.',
        'license_plate.regex' => 'Enter a valid Ugandan number plate (e.g. UBA 123A).',
    ]);
    $riderData['is_available'] = $request->boolean('is_available');
    $user->rider->update($riderData);

}
    // Let the user know specifically if their email changed
    $message = $oldEmail !== $validated['email']
        ? 'Your profile has been updated. Your email address was changed to ' . $validated['email'] . '.'
        : 'Your profile has been updated successfully.';

    return redirect()->route('profile.show')->with('success', $message);
}
}