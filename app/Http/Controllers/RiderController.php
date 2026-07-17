<?php
// app/Http/Controllers/RiderController.php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\PlatformEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiderController extends Controller
{
    public function dashboard()
    {
        $rider = Auth::user()->rider;

        // Safety check: redirect instead of crashing if this account
        // has the rider role but no matching rider profile row
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

    public function index()
    {
        $rider = Auth::user()->rider;

        if (! $rider) {
            return redirect()->route('profile.show')
                ->with('success', 'Your rider profile is incomplete. Please contact support.');
        }

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

    public function accept(OrderItem $orderItem)
    {
        $rider = Auth::user()->rider;

        abort_if(! $rider, 403, 'Your rider profile is incomplete.');
        abort_if($orderItem->status !== 'shipped' || $orderItem->rider_id !== null, 403);

        $orderItem->update(['rider_id' => $rider->id]);

        return back()->with('success', 'Delivery accepted. It now appears in your active deliveries.');
    }

    public function markDelivered(OrderItem $orderItem)
    {
        $rider = Auth::user()->rider;

        abort_if(! $rider, 403, 'Your rider profile is incomplete.');
        abort_if($orderItem->rider_id !== $rider->id, 403);

        $orderItem->update(['status' => 'delivered']);

        $vendor = $orderItem->vendor;
        $saleAmount = $orderItem->price * $orderItem->quantity;
        $commission = $saleAmount * ($vendor->commission_rate / 100);
        $vendorEarnings = $saleAmount - $commission;

        $vendor->increment('wallet_balance', $vendorEarnings);

        PlatformEarning::create([
            'order_item_id' => $orderItem->id,
            'vendor_id' => $vendor->id,
            'commission_amount' => $commission,
        ]);

        return back()->with('success', 'Delivery marked as completed.');
    }

    public function history()
    {
        $rider = Auth::user()->rider;

        if (! $rider) {
            return redirect()->route('profile.show')
                ->with('success', 'Your rider profile is incomplete. Please contact support.');
        }

        $deliveries = OrderItem::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->with('product', 'vendor', 'order.user')
            ->latest()
            ->get();

        return view('rider.deliveries.history', compact('deliveries'));
    }
}