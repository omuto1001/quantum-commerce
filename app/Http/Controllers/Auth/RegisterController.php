<?php
// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use App\Models\User;
use App\Models\Vendor;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    // ---------------- CUSTOMER ----------------

    public function showCustomerForm()
    {
        return view('auth.register-customer');
    }

    public function registerCustomer(Request $request)
    {
        $validated = $request->validate([
    'name'     => ['required', 'string', 'max:255'],
    'email'    => ['required', 'email', 'unique:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'],
    'phone'    => ['required', 'string', 'regex:/^[0-9]{10}$/'],
    'address'  => ['required', 'string', 'max:255'],
    'password' => ['required', 'string', 'min:8', 'confirmed'],
], [
    'email.regex' => 'Your email address must end in @gmail.com.',
    'phone.regex' => 'Your phone number must be 10 digits.',
]);

        $user = User::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'],
            'address'         => $validated['address'],
            'password'        => Hash::make($validated['password']),
            'approval_status' => 'approved',
        ]);

        $user->assignRole('customer');

        Auth::login($user);

        // Try to send the welcome email, but never let a mail failure
        // block registration - the account is already created above.
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        return redirect()->route('profile.show')
            ->with('success', 'Welcome! Your account has been created.');
    }

    // ---------------- VENDOR ----------------

    public function showVendorForm()
    {
        return view('auth.register-vendor');
    }

    public function registerVendor(Request $request)
    {
        $validated = $request->validate([
    'name'             => ['required', 'string', 'max:255'],
    'email'            => ['required', 'email', 'unique:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'],
    'phone'            => ['required', 'string', 'regex:/^[0-9]{10}$/'],
    'address'          => ['required', 'string', 'max:255'],
    'password'         => ['required', 'string', 'min:8', 'confirmed'],
    'shop_name'        => ['required', 'string', 'max:255'],
    'shop_description' => ['nullable', 'string'],
], [
    'email.regex' => 'Your email address must end in @gmail.com.',
    'phone.regex' => 'Your phone number must be 10 digits.',
]);
        $user = User::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'],
            'address'         => $validated['address'],
            'password'        => Hash::make($validated['password']),
            'approval_status' => 'pending',
        ]);

        $user->assignRole('vendor');

        Vendor::create([
            'user_id'          => $user->id,
            'shop_name'        => $validated['shop_name'],
            'shop_description' => $validated['shop_description'] ?? null,
        ]);

        Auth::login($user);

        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        return redirect()->route('approval.pending')
            ->with('success', 'Your vendor application has been submitted for review.');
    }

    // ---------------- RIDER ----------------

    public function showRiderForm()
    {
        return view('auth.register-rider');
    }

    public function registerRider(Request $request)
    {
      $validated = $request->validate([
    'name'               => ['required', 'string', 'max:255'],
    'email'              => ['required', 'email', 'unique:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'],
    'phone'              => ['required', 'string', 'regex:/^[0-9]{10}$/'],
    'address'            => ['required', 'string', 'max:255'],
    'password'           => ['required', 'string', 'min:8', 'confirmed'],
    'vehicle_type'       => ['required', 'string', 'in:Motorcycle,Bicycle,Car'],
    'license_plate'      => [
        Rule::requiredIf(in_array($request->vehicle_type, ['Motorcycle', 'Car'])),
        'nullable',
        'string',
        'regex:/^U[A-Z]{2}\s?\d{3}[A-Z]$/i',
    ],
    'national_id_number' => ['nullable', 'string', 'regex:/^[A-Z]{2}[A-Z0-9]{12}$/i'],
], [
    'email.regex' => 'Your email address must end in @gmail.com.',
    'phone.regex' => 'Your phone number must be 10 digits.',
    'license_plate.required' => 'A number plate is required for motorcycles and cars.',
    'license_plate.regex' => 'Enter a valid Ugandan number plate (e.g. UBA 123A).',
    'national_id_number.regex' => 'Enter a valid 14-character National ID number.',

]);

        $user = User::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'],
            'address'         => $validated['address'],
            'password'        => Hash::make($validated['password']),
            'approval_status' => 'pending',
        ]);

        $user->assignRole('rider');

        Rider::create([
            'user_id'            => $user->id,
            'vehicle_type'       => $validated['vehicle_type'],
            'license_plate'      => $validated['license_plate'] ?? null,
            'national_id_number' => $validated['national_id_number'] ?? null,
        ]);

        Auth::login($user);

        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        return redirect()->route('approval.pending')
            ->with('success', 'Your delivery agent application has been submitted for review.');
    }

    // Shown to vendors/riders while waiting for admin approval
    public function pendingApproval()
    {
        $user = Auth::user();

        if ($user->isApproved()) {
            return redirect()->route('profile.show');
        }

        return view('auth.pending-approval', compact('user'));
    }
}