<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\StockNotificationController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/api/products/{id}', [ProductController::class, 'showJson'])->name('products.show.json');
Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::post('reviews/{review}/reply', [ReviewController::class, 'reply'])->name('reviews.reply')->middleware('auth');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Search Route
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// Shipping Routes
Route::get('/shipping/provinces', [ShippingController::class, 'getProvinces'])->name('shipping.provinces');
Route::get('/shipping/cities', [ShippingController::class, 'getCities'])->name('shipping.cities');
Route::post('/shipping/cost', [ShippingController::class, 'getCost'])->name('shipping.cost');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');

// Stock Notification Route
Route::post('stock-notification', [StockNotificationController::class, 'store'])->name('stock.notification.store');

Route::get('/pesan-antar', function () {
    return view('pesan-antar');
})->name('pesan-antar');

Route::get('/promo', [ProductController::class, 'promo'])->name('promo');

// Auth Routes
Route::middleware('throttle:auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect /login GET requests to home
// Route::get('/login', function () {
//     return redirect('/');
// });

// Password Reset Routes
Route::get('forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'reset'])->name('password.update');

// Google Auth Routes
Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

// Admin Login Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [App\Http\Controllers\Auth\AdminLoginController::class, 'login']);
    });
    Route::post('logout', [App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('logout')->middleware('auth');
});

// Order Routes
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.index')->middleware('auth');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store')->middleware('auth');
Route::get('/order/success', [OrderController::class, 'success'])->name('order.success')->middleware('auth');
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('auth');
Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel')->middleware('auth');

// User Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

// Admin Routes

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::resource('users', AdminUserController::class);
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{review}/reply', [AdminReviewController::class, 'reply'])->name('reviews.reply');
    Route::get('notifications/{id}/read', [AdminNotificationController::class, 'read'])->name('notifications.read');
});