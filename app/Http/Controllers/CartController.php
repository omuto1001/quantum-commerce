<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Show the logged-in customer's cart with all items and the running total
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('product.vendor')
            ->get();

        // Calculate the total cost across all items
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    // Add a product to the cart, or increase quantity if it's already in there
    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $product->stock],
        ]);

        // Check if this product is already in the customer's cart
        $existingItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingItem) {
            // Already in cart - just increase the quantity
            $existingItem->update([
                'quantity' => $existingItem->quantity + $validated['quantity'],
            ]);
        } else {
            // New to cart - create a fresh cart item
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Added to cart.');
    }

    // Update the quantity of an existing cart item
    public function update(Request $request, CartItem $cartItem)
    {
        // Security check: customers can only modify their own cart items
        abort_if($cartItem->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $cartItem->product->stock],
        ]);

        $cartItem->update($validated);

        return back()->with('success', 'Cart updated.');
    }

    // Remove an item from the cart entirely
    public function remove(CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== Auth::id(), 403);

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}