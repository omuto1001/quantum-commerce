<?php
// app/Http/Controllers/RiderController.php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\PlatformEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiderController extends Controller
{
    // The rider's main dashboard - shows their stats
    public function dashboard()
{
    $rider = Auth::user()->rider;

    // Safety check: if this user has the rider role but no matching
    // rider profile row exists (e.g. role was changed manually without
    // creating the profile), redirect them instead of crashing
    if (! $rider) {
        return redirect()->route('profile.show')
            ->with('success', 'Your rider profile is incomplete. Please contact support.');
    }

    return view('rider.dashboard', [
        'rider' => $rider,
        'activeDeliveries' => $rider->orderItems()->whereIn('status', ['shipped'])->count(),
        'completedDeliveries' => $rider->orderItems()->where('status', 'delivered')->count(),
    ]);

    }

    // Shows deliveries available to accept (shipped by a vendor, no rider assigned yet)
    // PLUS this rider's own currently active deliveries
    public function index()
    {
        $rider = Auth::user()->rider;

        $availableDeliveries = OrderItem::where('status', 'shipped')
            ->whereNull('rider_id')
            ->with('product', 'vendor', 'order.user')
            ->latest()
            ->get();

        $myDeliveries = OrderItem::where('rider_id', $rider->id)
            ->whereIn('status', ['shipped'])
            ->with('product', 'vendor', 'order.user')
            ->latest()
            ->get();

        return view('rider.deliveries.index', compact('availableDeliveries', 'myDeliveries'));
    }

    // Rider accepts an available delivery, assigning it to themselves
    public function accept(OrderItem $orderItem)
    {
        abort_if($orderItem->status !== 'shipped' || $orderItem->rider_id !== null, 403);

        $orderItem->update(['rider_id' => Auth::user()->rider->id]);

        return back()->with('success', 'Delivery accepted. It now appears in your active deliveries.');
    }

    // Rider marks a delivery they're handling as delivered
    public function markDelivered(OrderItem $orderItem)
    {
        abort_if($orderItem->rider_id !== Auth::user()->rider->id, 403);

        $orderItem->update(['status' => 'delivered']);

        $vendor = $orderItem->vendor;
        $saleAmount = $orderItem->price * $orderItem->quantity;
        $commission = $saleAmount * ($vendor->commission_rate / 100);
        $vendorEarnings = $saleAmount - $commission;

        // Credit the vendor's wallet with their share
        $vendor->increment('wallet_balance', $vendorEarnings);

        // Record the platform's commission as actual revenue
        PlatformEarning::create([
            'order_item_id' => $orderItem->id,
            'vendor_id' => $vendor->id,
            'commission_amount' => $commission,
        ]);

        return back()->with('success', 'Delivery marked as completed.');
    }

    // History of everything this rider has delivered
    public function history()
    {
        $rider = Auth::user()->rider;

        $deliveries = OrderItem::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->with('product', 'vendor', 'order.user')
            ->latest()
            ->get();

        return view('rider.deliveries.history', compact('deliveries'));
    }
}