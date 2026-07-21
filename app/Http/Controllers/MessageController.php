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

    public function showWithVendor(Vendor $vendor)
{
    $user = Auth::user();
    abort_unless($user->isCustomer(), 403);

    $messages = Message::where('vendor_id', $vendor->id)
        ->whereNull('order_id')
        ->where(function ($q) use ($user, $vendor) {
            $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
        })
        ->orderBy('created_at')
        ->get();

    Message::where('vendor_id', $vendor->id)
        ->whereNull('order_id')
        ->where('receiver_id', $user->id)
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

    return view('messages.show-vendor', compact('vendor', 'messages'));
}

public function storeWithVendor(Request $request, Vendor $vendor)
{
    $user = Auth::user();
    abort_unless($user->isCustomer(), 403);

    $validated = $request->validate([
        'body' => ['required', 'string', 'max:2000'],
    ]);

    Message::create([
        'order_id'    => null,
        'vendor_id'   => $vendor->id,
        'sender_id'   => $user->id,
        'receiver_id' => $vendor->user_id,
        'body'        => $validated['body'],
    ]);

    return back();
}

public function storeAsVendor(Request $request, \App\Models\User $customer)
{
    $user = Auth::user();
    abort_unless($user->isVendor(), 403);
    $vendor = $user->vendor;

    $validated = $request->validate([
        'body' => ['required', 'string', 'max:2000'],
    ]);

    Message::create([
        'order_id'    => null,
        'vendor_id'   => $vendor->id,
        'sender_id'   => $user->id,
        'receiver_id' => $customer->id,
        'body'        => $validated['body'],
    ]);

    return back();
}

public function showAsVendor(\App\Models\User $customer)
{
    $user = Auth::user();
    abort_unless($user->isVendor(), 403);
    $vendor = $user->vendor;

    $messages = Message::where('vendor_id', $vendor->id)
        ->whereNull('order_id')
        ->where(function ($q) use ($customer, $user) {
            $q->where('sender_id', $customer->id)->orWhere('receiver_id', $customer->id);
        })
        ->orderBy('created_at')
        ->get();

    Message::where('vendor_id', $vendor->id)
        ->whereNull('order_id')
        ->where('sender_id', $customer->id)
        ->where('receiver_id', $user->id)
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

    return view('messages.vendor-reply', compact('customer', 'messages'));
}

public function vendorInbox()
{
    $user = Auth::user();
    abort_unless($user->isVendor(), 403);
    $vendor = $user->vendor;

    // Get one row per distinct customer this vendor has a pre-order thread with
    $conversations = Message::where('vendor_id', $vendor->id)
        ->whereNull('order_id')
        ->get()
        ->groupBy(function ($message) use ($user) {
            // Group by "the other person" in the conversation
            return $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
        })
        ->map(function ($messages) {
            return $messages->sortByDesc('created_at')->first();
        })
        ->sortByDesc('created_at');

    return view('messages.vendor-inbox', compact('conversations'));
}
}