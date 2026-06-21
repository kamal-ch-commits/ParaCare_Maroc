<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\AdminProductReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::get('/', [StorefrontController::class, 'home'])->name('storefront.home');
Route::get('/shop', [StorefrontController::class, 'index'])->name('storefront.products.index');
Route::get('/products/{product}', [StorefrontController::class, 'show'])->name('storefront.products.show');
Route::post('/products/{product}/reviews', [ProductReviewController::class, 'store'])
    ->middleware('auth')
    ->name('storefront.products.reviews.store');
Route::post('/locale/{locale}', LocaleController::class)->name('locale.switch');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/buy-now/{product}', [CheckoutController::class, 'startBuyNow'])->name('checkout.buy-now');
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/buy-now', [CheckoutController::class, 'createBuyNow'])->name('checkout.buy-now.create');
Route::post('/checkout/buy-now', [CheckoutController::class, 'storeBuyNow'])->name('checkout.buy-now.store');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

Route::middleware('auth')->group(function () {
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('suppliers', SupplierController::class)->except('show');
    Route::resource('products', ProductController::class)->except('show');
    Route::get('/reviews', [AdminProductReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [AdminProductReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{review}/reject', [AdminProductReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [AdminProductReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/stock-entries', [StockEntryController::class, 'index'])->name('stock-entries.index');
    Route::get('/stock-entries/create', [StockEntryController::class, 'create'])->name('stock-entries.create');
    Route::post('/stock-entries', [StockEntryController::class, 'store'])->name('stock-entries.store');

    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/orders/{order}/payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('admin.orders.payment.update');

    Route::get('/alerts/low-stock', [AlertController::class, 'lowStock'])->name('alerts.low-stock');
    Route::get('/alerts/expiration', [AlertController::class, 'expiration'])->name('alerts.expiration');

    Route::get('/history/stock-entries', [HistoryController::class, 'stockEntries'])->name('history.stock-entries');
    Route::get('/history/sales', [HistoryController::class, 'sales'])->name('history.sales');
});
