<?php
// app/Http/Controllers/VendorOrderController.php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorOrderController extends Controller
{
    // List every order item that belongs to the logged-in vendor,
    // across ALL customer orders (not just one)
    public function index()
    {
        $vendor = Auth::user()->vendor;

        $orderItems = OrderItem::where('vendor_id', $vendor->id)
            ->with('product', 'order.user') // load product info + which customer ordered it
            ->latest()
            ->get();

        return view('vendor.orders.index', compact('orderItems'));
    }

    // Update just this vendor's own portion of an order (their OrderItem only)
    public function updateStatus(Request $request, OrderItem $orderItem)
    {
        // Security check: vendors can only update their own order items
        abort_if($orderItem->vendor_id !== Auth::user()->vendor->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,shipped,delivered'],
        ]);

        $orderItem->update($validated);

        return back()->with('success', 'Order status updated.');
    }
}