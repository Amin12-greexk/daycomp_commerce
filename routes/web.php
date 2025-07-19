<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- PUBLIC SHOPPING ROUTES ---
// The homepage is now the product catalog
Route::get('/', [ShopController::class, 'index'])->name('shop.index');
// The single product page
Route::get('/products/{product:slug}', [ShopController::class, 'show'])->name('shop.show');


// --- AUTHENTICATED USER ROUTES ---
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware('auth');
Route::post('/checkout/success', [OrderController::class, 'handlePaymentSuccess'])->name('checkout.success')->middleware('auth');
// Main dashboard that redirects users based on their role after login
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    // Regular customers can be redirected to a dedicated account page
    return redirect()->route('customer.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Customer-specific routes (profile, order history, etc.)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // This is the customer's personal dashboard (e.g., for viewing orders)
    Route::get('/my-account', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
});


// --- ADMIN-ONLY ROUTES ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    // All other admin routes will go here
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::resource('users', AdminUserController::class);
    Route::post('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('/update/{rowId}', [CartController::class, 'update'])->name('update');
    Route::get('/remove/{rowId}', [CartController::class, 'remove'])->name('remove');
});
Route::post('/midtrans/notification', [OrderController::class, 'notificationHandler'])->name('midtrans.notification');


// Laravel Breeze authentication routes (login, register, etc.)
require __DIR__ . '/auth.php';