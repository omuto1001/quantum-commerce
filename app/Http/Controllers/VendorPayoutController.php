<?php
// app/Http/Controllers/VendorPayoutController.php

namespace App\Http\Controllers;

use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorPayoutController extends Controller
{
    // List this vendor's own payout history
    public function index()
    {
        $vendor = Auth::user()->vendor;

        $payouts = $vendor->payouts()->latest()->get();

        return view('vendor.payouts.index', [
            'payouts' => $payouts,
            'walletBalance' => $vendor->wallet_balance,
        ]);
    }

    // Vendor requests a payout of their current wallet balance
    public function store(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:' . $vendor->wallet_balance],
        ]);

        Payout::create([
            'vendor_id' => $vendor->id,
            'amount' => $validated['amount'],
            'status' => 'requested',
        ]);

        // Deduct immediately so the vendor can't request the same funds twice
        // while the payout is pending admin review
        $vendor->decrement('wallet_balance', $validated['amount']);

        return back()->with('success', 'Payout request submitted.');
    }
}