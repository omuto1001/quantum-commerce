<?php
// app/Http/Controllers/Admin/ApprovalController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\AccountApprovedMail;
use Illuminate\Support\Facades\Mail;

class ApprovalController extends Controller
{
    // List every vendor/rider still waiting for approval
   public function index()
{
    // Spatie's role() scope checks the model_has_roles pivot table,
    // since there's no plain 'role' column on users
    $pendingUsers = User::role(['vendor', 'rider'])
        ->where('approval_status', 'pending')
        ->with(['vendor', 'rider'])
        ->latest()
        ->get();

    return view('admin.approvals', compact('pendingUsers'));
}

    public function approve(User $user)
    {
        $user->update(['approval_status' => 'approved']);

        // Notify the vendor/rider that their account is now active
        Mail::to($user->email)->send(new AccountApprovedMail($user));

        return back()->with('success', "{$user->name}'s account has been approved.");
    }

    public function reject(User $user)
    {
        $user->update(['approval_status' => 'rejected']);

        return back()->with('success', "{$user->name}'s account has been rejected.");
    }

    // Shows the admin's main dashboard with platform-wide stats
    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalCustomers' => \App\Models\User::role('customer')->count(),
            'totalVendors'   => \App\Models\User::role('vendor')->count(),
            'totalRiders'    => \App\Models\User::role('rider')->count(),
            'pendingCount'   => \App\Models\User::role(['vendor', 'rider'])
                                    ->where('approval_status', 'pending')
                                    ->count(),
            'platformRevenue' => \App\Models\PlatformEarning::sum('commission_amount'),
        ]);
    }
}