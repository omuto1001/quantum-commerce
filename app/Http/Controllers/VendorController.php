<?php
// app/Http/Controllers/VendorController.php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    // Shows the logged-in vendor's own dashboard with their shop stats
    public function dashboard()
{
    $vendor = Auth::user()->vendor;
    

        // Safety check: redirect instead of crashing if this account
        // has the vendor role but no matching vendor profile row
        if (! $vendor) {
            return redirect()->route('profile.show')
                ->with('success', 'Your vendor profile is incomplete. Please contact support.');
        }

    return view('vendor.dashboard', [
        'vendor' => $vendor,
        'productCount' => $vendor->products()->count(),
        'orderCount' => $vendor->orderItems()->count(),
    ]);
}
}
