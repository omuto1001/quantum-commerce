<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PlatformEarning;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
   public function index()
{
    $totalRevenue = Order::sum('total_amount');
    $totalOrders = Order::count();
    $totalCommission = PlatformEarning::sum('commission_amount');
    $deliveredItems = OrderItem::where('status', 'delivered')->count();

    $topVendors = OrderItem::where('status', 'delivered')
        ->select('vendor_id', DB::raw('SUM(price * quantity) as total_sales'), DB::raw('COUNT(*) as items_sold'))
        ->groupBy('vendor_id')
        ->orderByDesc('total_sales')
        ->with('vendor')
        ->take(5)
        ->get();

    $topProducts = OrderItem::where('status', 'delivered')
        ->select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(price * quantity) as total_revenue'))
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->with('product')
        ->take(5)
        ->get();

    // Revenue for the last 7 days, formatted for Highcharts (labels + values as separate arrays)
    $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
        $date = now()->subDays($daysAgo);
        return [
            'date' => $date->format('D'),
            'total' => (float) Order::whereDate('created_at', $date->toDateString())->sum('total_amount'),
        ];
    });

    return view('admin.reports.index', compact(
        'totalRevenue', 'totalOrders', 'totalCommission', 'deliveredItems',
        'topVendors', 'topProducts', 'last7Days'
    ));
}

}