<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

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
}