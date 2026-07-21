<?php
// routes/web.php

use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// The homepage
Route::get('/', function () {
    return view('welcome');
    
});

// -------- Public product browsing (any logged-in user can view) --------
Route::middleware('auth')->group(function () {
    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
});

// -------- Cart (customers only) --------
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/item/{cartItem}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/item/{cartItem}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
});

// -------- Checkout (customers only) --------
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
});

// -------- Orders (customers only) --------
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::get('/payment/{order}/initiate', [\App\Http\Controllers\PaymentController::class, 'initiate'])->name('payment.initiate');
});
Route::middleware('auth')->group(function () {
    Route::get('/orders/{order}/messages/{vendor}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/orders/{order}/messages/{vendor}', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
});

Route::get('/vendors/{vendor}/messages', [\App\Http\Controllers\MessageController::class, 'showWithVendor'])->name('messages.vendor.show');
Route::post('/vendors/{vendor}/messages', [\App\Http\Controllers\MessageController::class, 'storeWithVendor'])->name('messages.vendor.store');

// -------- Public self-registration routes --------
Route::get('/register/customer', [RegisterController::class, 'showCustomerForm'])->name('register.customer');
Route::post('/register/customer', [RegisterController::class, 'registerCustomer']);

Route::get('/register/vendor', [RegisterController::class, 'showVendorForm'])->name('register.vendor');
Route::post('/register/vendor', [RegisterController::class, 'registerVendor']);

Route::get('/register/rider', [RegisterController::class, 'showRiderForm'])->name('register.rider');
Route::post('/register/rider', [RegisterController::class, 'registerRider']);

// -------- Logged-in routes (any role) --------
Route::middleware('auth')->group(function () {
    Route::get('/approval-pending', [RegisterController::class, 'pendingApproval'])->name('approval.pending');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

   Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isVendor()) return redirect()->route('vendor.dashboard');
    if ($user->isRider())  return redirect()->route('rider.dashboard');
    if ($user->isAdmin())  return redirect()->route('admin.dashboard');

    return redirect()->route('profile.show');
})->name('dashboard');
});

// -------- Admin-only routes --------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [ApprovalController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('admin.approvals');
    Route::post('/approvals/{user}/approve', [ApprovalController::class, 'approve'])->name('admin.approvals.approve');
    Route::post('/approvals/{user}/reject', [ApprovalController::class, 'reject'])->name('admin.approvals.reject');

    Route::get('/vendors', [\App\Http\Controllers\Admin\UserManagementController::class, 'vendors'])->name('admin.vendors');
    Route::get('/riders', [\App\Http\Controllers\Admin\UserManagementController::class, 'riders'])->name('admin.riders');
    Route::get('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'show'])->name('admin.users.show');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('admin.users.destroy');

    // Roles & Permissions management
    Route::get('/roles', [\App\Http\Controllers\Admin\RolePermissionController::class, 'index'])->name('admin.roles.index');
    Route::put('/roles/{role}/permissions', [\App\Http\Controllers\Admin\RolePermissionController::class, 'updatePermissions'])->name('admin.roles.permissions.update');

    Route::get('/users-roles', [\App\Http\Controllers\Admin\RolePermissionController::class, 'users'])->name('admin.roles.users');
    Route::put('/users-roles/{user}', [\App\Http\Controllers\Admin\RolePermissionController::class, 'updateUserRole'])->name('admin.roles.users.update');
    Route::get('/payouts', [\App\Http\Controllers\Admin\PayoutController::class, 'index'])->name('admin.payouts.index');
Route::put('/payouts/{payout}/approve', [\App\Http\Controllers\Admin\PayoutController::class, 'approve'])->name('admin.payouts.approve');
Route::put('/payouts/{payout}/paid', [\App\Http\Controllers\Admin\PayoutController::class, 'markPaid'])->name('admin.payouts.paid');
Route::post('/payouts/{payout}/reject', [\App\Http\Controllers\Admin\PayoutController::class, 'reject'])->name('admin.payouts.reject');
Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');

// Categories management
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories.index');
Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store');
Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.categories.update');
Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
});

// -------- Vendor-only routes --------
Route::middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/vendor/dashboard', [\App\Http\Controllers\VendorController::class, 'dashboard'])->name('vendor.dashboard');

    Route::get('/vendor/products', [\App\Http\Controllers\VendorProductController::class, 'index'])->name('vendor.products.index');
    Route::get('/vendor/products/create', [\App\Http\Controllers\VendorProductController::class, 'create'])->name('vendor.products.create');
    Route::get('/vendor/products/{product}', [\App\Http\Controllers\VendorProductController::class, 'show'])->name('vendor.products.show');
    Route::post('/vendor/products', [\App\Http\Controllers\VendorProductController::class, 'store'])->name('vendor.products.store');
    Route::get('/vendor/products/{product}/edit', [\App\Http\Controllers\VendorProductController::class, 'edit'])->name('vendor.products.edit');
    Route::put('/vendor/products/{product}', [\App\Http\Controllers\VendorProductController::class, 'update'])->name('vendor.products.update');
    Route::delete('/vendor/products/{product}', [\App\Http\Controllers\VendorProductController::class, 'destroy'])->name('vendor.products.destroy');
    Route::get('/vendor/orders', [\App\Http\Controllers\VendorOrderController::class, 'index'])->name('vendor.orders.index');
Route::put('/vendor/orders/{orderItem}', [\App\Http\Controllers\VendorOrderController::class, 'updateStatus'])->name('vendor.orders.update');
    Route::get('/vendor/payouts', [\App\Http\Controllers\VendorPayoutController::class, 'index'])->name('vendor.payouts.index');
Route::post('/vendor/payouts', [\App\Http\Controllers\VendorPayoutController::class, 'store'])->name('vendor.payouts.store');

});
// -------- Rider-only routes --------
Route::middleware(['auth', 'role:rider'])->group(function () {
    Route::get('/rider/dashboard', [\App\Http\Controllers\RiderController::class, 'dashboard'])->name('rider.dashboard');
    Route::get('/rider/deliveries', [\App\Http\Controllers\RiderController::class, 'index'])->name('rider.deliveries.index');
    Route::post('/rider/deliveries/{orderItem}/accept', [\App\Http\Controllers\RiderController::class, 'accept'])->name('rider.deliveries.accept');
    Route::put('/rider/deliveries/{orderItem}/complete', [\App\Http\Controllers\RiderController::class, 'markDelivered'])->name('rider.deliveries.complete');
    Route::get('/rider/history', [\App\Http\Controllers\RiderController::class, 'history'])->name('rider.history');
});

// -------- Reviews (customers only) --------
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/order-items/{orderItem}/review', [\App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/order-items/{orderItem}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});
Route::middleware('auth')->group(function () {
    Route::get('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');
});
// Breeze's login/logout/password-reset routes - MUST be included
require __DIR__.'/auth.php';