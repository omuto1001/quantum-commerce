<?php
// app/Http/Controllers/ReviewController.php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Show the review form for a specific delivered order item
    public function create(OrderItem $orderItem)
    {
        
        // Security checks: must belong to this customer, must be delivered,
        // and must not already have a review
        abort_if($orderItem->order->user_id !== Auth::id(), 403);
        abort_if($orderItem->status !== 'delivered', 403, 'You can only review delivered items.');
        abort_if($orderItem->review()->exists(), 403, 'You have already reviewed this item.');

        return view('reviews.create', compact('orderItem'));
    }

    // Store the review
    public function store(Request $request, OrderItem $orderItem)
    {
        abort_if($orderItem->order->user_id !== Auth::id(), 403);
        abort_if($orderItem->status !== 'delivered', 403);
        abort_if($orderItem->review()->exists(), 403);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $orderItem->product_id,
            'order_item_id' => $orderItem->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('orders.show', $orderItem->order_id)
            ->with('success', 'Thank you for your review!');
    }
}