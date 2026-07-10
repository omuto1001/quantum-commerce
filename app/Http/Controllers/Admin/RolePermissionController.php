<?php
// app/Http/Controllers/Admin/RolePermissionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    // List all roles with their currently assigned permissions
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $allPermissions = Permission::all();

        return view('admin.roles.index', compact('roles', 'allPermissions'));
    }

    // Update which permissions belong to a specific role
    public function updatePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions'   => ['array'],       // checkbox list, can be empty
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        // syncPermissions replaces the role's entire permission set
        // with exactly what was checked on the form
        $role->syncPermissions($validated['permissions'] ?? []);

        return back()->with('success', "Permissions updated for the '{$role->name}' role.");
    }

    // List every user so admin can change their role from the UI
    public function users()
    {
        $users = User::with('roles')->latest()->get();

        return view('admin.roles.users', compact('users'));
    }

    // Change a single user's role (e.g. promote a customer to admin, or fix a mistake)
    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        // syncRoles replaces all of a user's roles with just this one,
        // since our platform uses single-role accounts
        $user->syncRoles([$validated['role']]);

        return back()->with('success', "{$user->name}'s role has been updated to {$validated['role']}.");
    }
}