<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Abort;

class MessageController extends Controller
{
    // Show the message thread for a given order + vendor pair
    public function show(Order $order, Vendor $vendor)
    {
        $this->authorizeAccess($order, $vendor);

        $messages = Message::where('order_id', $order->id)
            ->where('vendor_id', $vendor->id)
            ->orderBy('created_at')
            ->get();

        // Mark messages sent TO the current user as read
        Message::where('order_id', $order->id)
            ->where('vendor_id', $vendor->id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', compact('order', 'vendor', 'messages'));
    }

    public function store(Request $request, Order $order, Vendor $vendor)
    {
        $this->authorizeAccess($order, $vendor);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $user = Auth::user();

        // Figure out who's on the other end of this conversation
        $receiverId = $user->isVendor()
            ? $order->user_id
            : $vendor->user_id;

        Message::create([
            'order_id'    => $order->id,
            'vendor_id'   => $vendor->id,
            'sender_id'   => $user->id,
            'receiver_id' => $receiverId,
            'body'        => $validated['body'],
        ]);

        return back();
    }

    // Only the customer who placed the order, or the vendor who has an
    // item in it, can access this thread.
    private function authorizeAccess(Order $order, Vendor $vendor): void
    {
        $user = Auth::user();

        $isOwningCustomer = $user->isCustomer() && $order->user_id === $user->id;
        $isRelevantVendor = $user->isVendor() && $vendor->user_id === $user->id
            && $order->items()->where('vendor_id', $vendor->id)->exists();

        abort_unless($isOwningCustomer || $isRelevantVendor, 403);
    }
}