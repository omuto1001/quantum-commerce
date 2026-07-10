<?php
// app/Models/Vendor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
// A vendor can have many products
public function products()
{
    return $this->hasMany(Product::class);
}

    protected $fillable = [
        'user_id', 'shop_name', 'shop_description',
        'shop_logo', 'commission_rate', 'wallet_balance',
    ];

    // Every vendor profile belongs to one user account
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // A vendor receives many order items across different customer orders
public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}

// A vendor can have many payout requests over time
public function payouts()
{
    return $this->hasMany(Payout::class);
}
}
