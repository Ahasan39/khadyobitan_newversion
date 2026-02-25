<?php

use Illuminate\Support\Facades\Route;
use Modules\HomePageOne\Http\Controllers\HomePageOneController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('homepageones', HomePageOneController::class)->names('homepageone');
});

Route::get('homepageones', [HomePageOneController::class, 'index'])->name('homepageone.index');
Route::get('product-details', [HomePageOneController::class, 'productDetails'])->name('homepageone.productDetails');
Route::get('checkout', [HomePageOneController::class, 'checkout'])->name('homepageone.checkout');
Route::get('order-success', function () {
    return view('homepageone::frontend.order-success');
})->name('order.success');
