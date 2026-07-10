<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    // A category can have many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Automatically generate a URL-friendly slug from the name whenever
    // a category is created, so admins don't have to type slugs manually
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }
}