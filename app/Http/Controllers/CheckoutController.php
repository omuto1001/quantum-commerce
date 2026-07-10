<?php
// app/Http/Controllers/CheckoutController.php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Show the checkout confirmation page with cart summary and delivery address form
    public function show()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('product.vendor')
            ->get();

        // Don't let customers reach checkout with an empty cart
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('success', 'Your cart is empty.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.show', compact('cartItems', 'total'));
    }

    // Process the order: create one Order, split into OrderItems per vendor,
    // reduce product stock, then clear the cart.
    public function process(Request $request)
{
    $validated = $request->validate([
        'delivery_address' => ['required', 'string', 'max:255'],
        'payment_method' => ['required', 'in:mobile_money,card,cash_on_delivery'],
    ]);

    $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart.index')->with('success', 'Your cart is empty.');
    }

    $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

    // Create the order first, regardless of payment method - we always
    // need an Order row to attach a payment reference to.
    $order = DB::transaction(function () use ($cartItems, $validated, $total) {
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $total,
            'delivery_address' => $validated['delivery_address'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'vendor_id' => $item->product->vendor_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'status' => 'pending',
            ]);

            $item->product->decrement('stock', $item->quantity);
        }

        CartItem::where('user_id', Auth::id())->delete();

        return $order;
    });

    // Cash on Delivery: order is placed immediately, no payment gateway needed
    if ($validated['payment_method'] === 'cash_on_delivery') {
        return redirect()->route('orders.index')->with('success', 'Order placed successfully! Pay on delivery.');
    }

    // Mobile Money or Card: redirect to Flutterwave's hosted payment page
    return redirect()->route('payment.initiate', $order);
}
}