<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'user_id', 'total_amount', 'delivery_address',
    'payment_status', 'payment_method', 'payment_reference',
];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // An order can contain items from multiple vendors
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // An order item can have exactly one review (since it's a one-time purchase)
public function review()
{
    return $this->hasOne(Review::class);
}
}