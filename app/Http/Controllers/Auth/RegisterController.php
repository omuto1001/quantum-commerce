<?php
// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    // ---------------- CUSTOMER ----------------

    public function showCustomerForm()
    {
        return view('auth.register-customer');
    }

    public function registerCustomer(Request $request)
    {
        // Validate input before touching the database
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:20'],
            'address'  => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Customers are approved automatically, no admin review needed
        $user = User::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'],
            'address'         => $validated['address'],
            'password'        => Hash::make($validated['password']),
            'approval_status' => 'approved',
        ]);

        // Assign the 'customer' role via Spatie
        $user->assignRole('customer');

        Auth::login($user); // log them straight in

        // Send welcome confirmation email
Mail::to($user->email)->send(new WelcomeMail($user));

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
            'email'            => ['required', 'email', 'unique:users,email'],
            'phone'            => ['required', 'string', 'max:20'],
            'address'          => ['required', 'string', 'max:255'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
            'shop_name'        => ['required', 'string', 'max:255'],
            'shop_description' => ['nullable', 'string'],
        ]);

        // Vendor account starts as 'pending' - can't use dashboard yet
        $user = User::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'],
            'address'         => $validated['address'],
            'password'        => Hash::make($validated['password']),
            'approval_status' => 'pending',
        ]);

        // Assign the 'vendor' role via Spatie
        $user->assignRole('vendor');

        // Save the vendor's shop-specific details
        Vendor::create([
            'user_id'          => $user->id,
            'shop_name'        => $validated['shop_name'],
            'shop_description' => $validated['shop_description'] ?? null,
        ]);

        Auth::login($user); // logged in, but middleware blocks the dashboard

       Mail::to($user->email)->send(new WelcomeMail($user));

return redirect()->route('approval.pending')
    ->with('success', 'Your vendor application has been submitted for review.')
;
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
            'email'              => ['required', 'email', 'unique:users,email'],
            'phone'              => ['required', 'string', 'max:20'],
            'address'            => ['required', 'string', 'max:255'],
            'password'           => ['required', 'string', 'min:8', 'confirmed'],
            'vehicle_type'       => ['required', 'string', 'max:100'],
            'license_plate'      => ['nullable', 'string', 'max:50'],
            'national_id_number' => ['nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'],
            'address'         => $validated['address'],
            'password'        => Hash::make($validated['password']),
            'approval_status' => 'pending',
        ]);

        // Assign the 'rider' role via Spatie
        $user->assignRole('rider');

        Rider::create([
            'user_id'            => $user->id,
            'vehicle_type'       => $validated['vehicle_type'],
            'license_plate'      => $validated['license_plate'] ?? null,
            'national_id_number' => $validated['national_id_number'] ?? null,
        ]);

        Auth::login($user);

       Mail::to($user->email)->send(new WelcomeMail($user));

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