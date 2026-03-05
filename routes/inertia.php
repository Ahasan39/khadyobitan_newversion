<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\InertiaHomeController;
use App\Http\Controllers\Frontend\InertiaShopController;
use App\Http\Controllers\Frontend\InertiaProductController;
use App\Http\Controllers\Frontend\InertiaCartController;
use App\Http\Controllers\Frontend\InertiaCheckoutController;
use App\Http\Controllers\Frontend\InertiaPageController;
use App\Http\Controllers\Frontend\InertiaBlogController;
use App\Http\Controllers\Frontend\InertiaAuthController;
use App\Http\Controllers\Frontend\InertiaAccountController;
use App\Http\Controllers\Frontend\InertiaOrderTrackingController;
use App\Http\Controllers\Frontend\InertiaWishlistController;

/**
 * Inertia Frontend Routes
 * All routes for the React frontend using Inertia.js
 */

Route::group(['middleware' => ['web', 'ipcheck', 'check_refer']], function () {
    
    // ==================== PUBLIC ROUTES ====================
    
    // Homepage
    Route::get('/', [InertiaHomeController::class, 'index'])->name('home');
    
    // Shop & Products
    Route::get('/shop', [InertiaShopController::class, 'index'])->name('shop');
    Route::get('/api/shop/load-more', [InertiaShopController::class, 'loadMore'])->name('shop.loadMore');
    Route::get('/product/{slug}', [InertiaProductController::class, 'show'])->name('product.show');
    Route::post('/api/product/check-stock', [InertiaProductController::class, 'checkStock'])->name('product.checkStock');
    Route::get('/api/product/variables', [InertiaProductController::class, 'getVariables'])->name('product.getVariables');
    
    // Cart
    Route::get('/cart', [InertiaCartController::class, 'index'])->name('cart.show');
    Route::post('/api/cart/add', [InertiaCartController::class, 'add'])->name('cart.add');
    Route::post('/api/cart/remove', [InertiaCartController::class, 'remove'])->name('cart.remove');
    Route::post('/api/cart/update', [InertiaCartController::class, 'update'])->name('cart.update');
    Route::post('/api/cart/shipping', [InertiaCartController::class, 'updateShipping'])->name('cart.updateShipping');
    Route::post('/api/cart/clear', [InertiaCartController::class, 'clear'])->name('cart.clear');
    Route::get('/api/cart/content', [InertiaCartController::class, 'getContent'])->name('cart.getContent');
    
    // Wishlist
    Route::get('/wishlist', [InertiaWishlistController::class, 'index'])->name('wishlist.show');
    Route::post('/api/wishlist/add', [InertiaWishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/api/wishlist/remove', [InertiaWishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('/api/wishlist/check', [InertiaWishlistController::class, 'check'])->name('wishlist.check');
    
    // Authentication
    Route::get('/login', [InertiaAuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/login', [InertiaAuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [InertiaAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/register', [InertiaAuthController::class, 'register'])->name('auth.register');
    Route::post('/logout', [InertiaAuthController::class, 'logout'])->name('customer.logout');
    
    // Order Tracking
    Route::get('/track-order', [InertiaOrderTrackingController::class, 'index'])->name('customer.order_track');
    Route::post('/api/order/search', [InertiaOrderTrackingController::class, 'search'])->name('order.search');
    Route::get('/order-confirmation/{invoiceId}', [InertiaOrderTrackingController::class, 'confirmation'])->name('order.confirmation');
    
    // Static Pages
    Route::get('/about', [InertiaPageController::class, 'about'])->name('page.about');
    Route::get('/contact', [InertiaPageController::class, 'contact'])->name('page.contact');
    Route::get('/faq', [InertiaPageController::class, 'faq'])->name('page.faq');
    Route::get('/privacy', [InertiaPageController::class, 'privacy'])->name('page.privacy');
    Route::get('/terms', [InertiaPageController::class, 'terms'])->name('page.terms');
    Route::get('/shipping', [InertiaPageController::class, 'shipping'])->name('page.shipping');
    Route::get('/returns', [InertiaPageController::class, 'returns'])->name('page.returns');
    Route::get('/page/{slug}', [InertiaPageController::class, 'show'])->name('page.show');
    
    // Blog
    Route::get('/blog', [InertiaBlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [InertiaBlogController::class, 'show'])->name('blog.show');
    
    // ==================== PROTECTED ROUTES (Authenticated Customers) ====================
    
    Route::group(['middleware' => ['customer']], function () {
        
        // Checkout
        Route::get('/checkout', [InertiaCheckoutController::class, 'index'])->name('checkout.show');
        Route::post('/api/checkout/store', [InertiaCheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/api/checkout/areas', [InertiaCheckoutController::class, 'getAreas'])->name('checkout.getAreas');
        
        // Account & Dashboard
        Route::get('/account', [InertiaAccountController::class, 'index'])->name('account.show');
        Route::get('/account/profile-edit', [InertiaAccountController::class, 'editProfile'])->name('account.editProfile');
        Route::post('/api/account/profile-update', [InertiaAccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::get('/account/change-password', [InertiaAccountController::class, 'changePassword'])->name('account.changePassword');
        Route::post('/api/account/password-update', [InertiaAccountController::class, 'updatePassword'])->name('account.updatePassword');
        Route::get('/account/orders', [InertiaAccountController::class, 'orders'])->name('account.orders');
        Route::get('/account/orders/{id}', [InertiaAccountController::class, 'orderDetail'])->name('account.orderDetail');
    });
});
