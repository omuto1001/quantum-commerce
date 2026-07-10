<?php
// app/Http/Controllers/Admin/UserManagementController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserManagementController extends Controller
{
    // List every vendor (approved, pending, or rejected) - not just pending ones
    public function vendors()
    {
        $vendors = User::role('vendor')
            ->with('vendor') // load shop details
            ->latest()
            ->get();

        return view('admin.vendors', compact('vendors'));
    }

    // List every rider (approved, pending, or rejected)
    public function riders()
    {
        $riders = User::role('rider')
            ->with('rider') // load vehicle details
            ->latest()
            ->get();

        return view('admin.riders', compact('riders'));
    }

    // Show full details for a single vendor or rider
    public function show(User $user)
    {
        $user->load(['vendor', 'rider']);

        return view('admin.user-show', compact('user'));
    }

    // Permanently delete a vendor or rider account.
    // Deleting the User row also deletes their vendor/rider profile
    // automatically, because of the onDelete('cascade') we set in
    // the migrations back in Step 2.
    public function destroy(User $user)
    {
        $role = $user->roleLabel();
        $name = $user->name;

        $user->delete();

        return back()->with('success', "{$role} account for {$name} has been deleted.");
    }
}