<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles; // gives us assignRole(), hasRole(), etc.

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    // Fields allowed to be mass-assigned (e.g. User::create([...]))
    protected $fillable = [
        'name', 'email', 'password',
        'approval_status', 'phone', 'address', 'profile_photo',
    ];

    // Never expose these when the model is turned into JSON/array
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // A vendor user has exactly one vendor profile with shop info
    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    // A rider user has exactly one rider profile with delivery info
    public function rider()
    {
        return $this->hasOne(Rider::class);
    }

    // ---- Role helper methods, now powered by Spatie's hasRole() ----
    // These make role checks read cleanly in controllers and Blade views,
    // e.g. @if(auth()->user()->isVendor())

    public function isCustomer(): bool { return $this->hasRole('customer'); }
    public function isVendor(): bool   { return $this->hasRole('vendor'); }
    public function isRider(): bool    { return $this->hasRole('rider'); }
    public function isAdmin(): bool    { return $this->hasRole('admin'); }

    public function isApproved(): bool { return $this->approval_status === 'approved'; }
    public function isPending(): bool  { return $this->approval_status === 'pending'; }
    public function isRejected(): bool { return $this->approval_status === 'rejected'; }

    // Nicely formatted label for the UI, e.g. "Delivery Agent" not "rider".
    // Reads the user's first Spatie role and formats it for display.
    public function roleLabel(): string
    {
        $role = $this->getRoleNames()->first() ?? 'unknown';

        return match ($role) {
            'customer' => 'Customer',
            'vendor'   => 'Vendor',
            'rider'    => 'Delivery Agent',
            'admin'    => 'Administrator',
            default    => ucfirst($role),
        };
    }

    // A customer can have many cart items
public function cartItems()
{
    return $this->hasMany(CartItem::class);
}

// A customer can have many orders
public function orders()
{
    return $this->hasMany(Order::class);
}

}