<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    // Shows every active product from every approved vendor, for customers to browse
public function index(\Illuminate\Http\Request $request)
{
    $query = Product::where('status', 'active')
        ->whereHas('vendor', function ($q) {
            $q->whereHas('user', fn($uq) => $uq->where('approval_status', 'approved'));
        })
        ->with('vendor', 'category');

    // Search by product name if a search term was provided
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Filter by category if one was selected
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    $products = $query->latest()->get();
    $categories = \App\Models\Category::all();

    return view('products.index', compact('products', 'categories'));
}

    // Shows full details for a single product, plus an "Add to Cart" form
public function show(Product $product)
{
    abort_if($product->status !== 'active', 404);

    $product->load('reviews.user');
    $averageRating = $product->reviews->avg('rating');

    return view('products.show', compact('product', 'averageRating'));
}
}