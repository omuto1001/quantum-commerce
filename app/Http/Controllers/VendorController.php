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

    return view('vendor.dashboard', [
        'vendor' => $vendor,
        'productCount' => $vendor->products()->count(),
        'orderCount' => $vendor->orderItems()->count(),
    ]);
}
}
