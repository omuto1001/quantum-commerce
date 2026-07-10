<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'vendor_id', 'name', 'description', 'price', 'stock', 'image', 'status',
    ];

    // Every product belongs to one vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    // Every product optionally belongs to one category
public function category()
{
    return $this->belongsTo(Category::class);
}
// A product can have many reviews from different customers
public function reviews()
{
    return $this->hasMany(Review::class);
}
}