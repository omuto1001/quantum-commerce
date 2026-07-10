<?php
// app/Http/Controllers/Admin/PayoutController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    // List every payout request across all vendors
    public function index()
    {
        $payouts = Payout::with('vendor.user')->latest()->get();

        return view('admin.payouts.index', compact('payouts'));
    }

    // Approve a payout - marks it as approved (admin still needs to actually
    // send the money outside the system, then mark it "paid" separately)
    public function approve(Payout $payout)
    {
        $payout->update(['status' => 'approved']);

        return back()->with('success', 'Payout approved.');
    }

    // Mark an already-approved payout as fully paid out
    public function markPaid(Payout $payout)
    {
        $payout->update(['status' => 'paid']);

        return back()->with('success', 'Payout marked as paid.');
    }

    // Reject a payout - refunds the amount back into the vendor's wallet
    public function reject(Request $request, Payout $payout)
    {
        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:255'],
        ]);

        $payout->update([
            'status' => 'rejected',
            'admin_note' => $validated['admin_note'] ?? null,
        ]);

        // Refund the wallet since the payout didn't go through
        $payout->vendor->increment('wallet_balance', $payout->amount);

        return back()->with('success', 'Payout rejected and refunded to vendor wallet.');
    }
}