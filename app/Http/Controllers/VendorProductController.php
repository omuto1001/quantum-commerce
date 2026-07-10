<?php
// app/Http/Controllers/VendorProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorProductController extends Controller
{
    // List only this vendor's own products
    public function index()
{
    $products = Auth::user()->vendor->products()->with('reviews')->latest()->get();

    return view('vendor.products.index', compact('products'));
}

    public function create()
{
    $categories = \App\Models\Category::all();
    return view('vendor.products.create', compact('categories'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'image'       => ['nullable', 'image', 'max:2048'], // max 2MB
        ]);

        // Handle image upload if one was provided
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Attach the product to the logged-in vendor's own profile automatically
        Auth::user()->vendor->products()->create($validated);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product added successfully.');
    }

    // Shows full details for a single product belonging to the logged-in vendor
public function show(Product $product)
{
    abort_if($product->vendor_id !== Auth::user()->vendor->id, 403);

    $product->load('reviews.user');
    $averageRating = $product->reviews->avg('rating');

    return view('vendor.products.show', compact('product', 'averageRating'));
}

    public function edit(Product $product)
{
    abort_if($product->vendor_id !== Auth::user()->vendor->id, 403);

    $categories = \App\Models\Category::all();
    return view('vendor.products.edit', compact('product', 'categories'));
}

    public function update(Request $request, Product $product)
    {
        abort_if($product->vendor_id !== Auth::user()->vendor->id, 403);

        $validated = $request->validate([
    'name'        => ['required', 'string', 'max:255'],
    'category_id' => ['nullable', 'exists:categories,id'],
    'description' => ['nullable', 'string'],
    'price'       => ['required', 'numeric', 'min:0'],
    'stock'       => ['required', 'integer', 'min:0'],
    'image'       => ['nullable', 'image', 'max:2048'],
]);

        if ($request->hasFile('image')) {
            // Delete the old image file if one exists, to avoid piling up unused files
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        abort_if($product->vendor_id !== Auth::user()->vendor->id, 403);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }
}