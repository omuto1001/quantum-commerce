<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // List every order the logged-in customer has placed
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product', 'items.vendor')
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    // Show full details for a single order, including per-vendor status
    public function show(Order $order)
    {
        // Security check: customers can only view their own orders
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load('items.product', 'items.vendor');

        return view('orders.show', compact('order'));
    }

    // Customer cancels a specific item in their order, only allowed
// before it has been shipped
public function cancelItem(Request $request, \App\Models\OrderItem $orderItem)
{
    // Security check: only the customer who placed this order can cancel it
    abort_if($orderItem->order->user_id !== Auth::id(), 403);

    // Only pending or confirmed items can be cancelled - once shipped,
    // it's already with a rider and too late to simply cancel
    abort_if(! in_array($orderItem->status, ['pending', 'confirmed']), 403,
        'This item can no longer be cancelled since it has already been shipped.');

    $validated = $request->validate([
        'cancellation_reason' => ['nullable', 'string', 'max:500'],
    ]);

    $orderItem->update([
        'status' => 'cancelled',
        'cancellation_reason' => $validated['cancellation_reason'] ?? null,
        'cancelled_at' => now(),
    ]);

    // Restore the stock that was reserved for this cancelled item
    $orderItem->product->increment('stock', $orderItem->quantity);

    return back()->with('success', 'Item cancelled successfully.');
}
}