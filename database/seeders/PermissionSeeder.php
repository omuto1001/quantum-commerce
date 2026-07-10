<?php
// database/seeders/PermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Every permission in the system, grouped by who normally needs it.
        // firstOrCreate avoids duplicate errors on re-running the seeder.
        $permissions = [
            // ---- Account / profile (every role needs these on their OWN data) ----
            'view own profile',
            'edit own profile',

            // ---- Customer permissions ----
            'browse products',
            'manage own cart',
            'place orders',
            'view own orders',
            'cancel own orders',
            'write reviews',

            // ---- Vendor permissions (own shop only) ----
            'manage own shop',
            'manage own products',
            'view own vendor orders',
            'update own order status',
            'view own wallet',
            'request payout',

            // ---- Rider permissions (own deliveries only) ----
            'view own deliveries',
            'update delivery status',
            'toggle own availability',
            'view own delivery earnings',

            // ---- Admin / platform-wide permissions ----
            'manage vendors',        // view/delete any vendor account
            'manage riders',         // view/delete any rider account
            'manage customers',      // view/delete any customer account
            'approve applications',  // approve/reject pending vendor & rider signups
            'manage products',       // add/edit/delete ANY product platform-wide
            'manage orders',         // view/manage ALL orders platform-wide
            'view deliveries',       // view ALL delivery/rider assignment data
            'manage categories',     // create/edit/delete product categories
            'manage payouts',        // approve/reject vendor payout requests
            'view reports',          // sales analytics, platform-wide stats
            'manage roles',          // assign/edit roles and permissions themselves
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ---- Assign sensible default permissions to each role ----

        $customer = Role::where('name', 'customer')->first();
        $customer->syncPermissions([
            'view own profile',
            'edit own profile',
            'browse products',
            'manage own cart',
            'place orders',
            'view own orders',
            'cancel own orders',
            'write reviews',
        ]);

        $vendor = Role::where('name', 'vendor')->first();
        $vendor->syncPermissions([
            'view own profile',
            'edit own profile',
            'manage own shop',
            'manage own products',
            'view own vendor orders',
            'update own order status',
            'view own wallet',
            'request payout',
        ]);

        $rider = Role::where('name', 'rider')->first();
        $rider->syncPermissions([
            'view own profile',
            'edit own profile',
            'view own deliveries',
            'update delivery status',
            'toggle own availability',
            'view own delivery earnings',
        ]);

        // Admin gets EVERY permission that exists, including all of the above,
        // since an admin can act on behalf of / oversee every role.
        $admin = Role::where('name', 'admin')->first();
        $admin->syncPermissions($permissions);
    }
}