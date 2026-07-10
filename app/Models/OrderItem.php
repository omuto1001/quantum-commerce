<?php
// app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'vendor_id', 'rider_id', 'quantity', 'price', 'status'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // The rider assigned to deliver this specific order item, if any
    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    // An order item can have exactly one review (since it's a one-time purchase)
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}