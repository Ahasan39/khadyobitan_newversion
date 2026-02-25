<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FrontendController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\UpdateController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ThemeColorController;
use App\Http\Controllers\Api\IncompleteOrderApiController;
use App\Http\Controllers\Api\FeatureToggleController;
use App\Http\Controllers\UpdateClientController;


Route::group([
    'namespace' => 'Api',
    'prefix' => 'v1',
    'middleware' => ['api', 'throttle:200,1']
], function () {
    
     Route::get('app-config', [FrontendController::class, 'appconfig']);
     Route::get('slider', [FrontendController::class, 'slider']);
     Route::get('banner/{id?}', [FrontendController::class, 'banner']);
     Route::get('categories', [FrontendController::class, 'category']);
     
     //feroj works
    //  Route::get('products-by-category/{id}', [FrontendController::class, 'productsbycategory']);
     Route::get('category/{id}', [FrontendController::class, 'productsbycategory']); //product by category api

     Route::get('sub-categories', [FrontendController::class, 'subcategory']);
     Route::get('products-by-subcategory/{slug}', [FrontendController::class, 'productsbySubcategory']);
     Route::get('child-categories', [FrontendController::class, 'childcategory']);
     Route::get('products-by-childcategory/{slug}', [FrontendController::class, 'productsbyChildcategory']);
     Route::get('brands', [FrontendController::class, 'brand']);
     Route::get('products-by-brand/{slug}', [FrontendController::class, 'productsbyBrand']);
     Route::get('colors', [FrontendController::class, 'color']);
     Route::get('sizes', [FrontendController::class, 'size']);
     Route::get('reviews', [FrontendController::class, 'review']);
     
     Route::get('featured-product/{id?}', [FrontendController::class, 'featuredproduct']);
     Route::get('latest-product/{slug?}', [FrontendController::class, 'latestproduct']);  //new arrival product
     Route::get('popular-product/{id?}', [FrontendController::class, 'popularproduct']);
     Route::get('trending-product/{id?}', [FrontendController::class, 'trendingproduct']);
     Route::get('best-selling-product/{id?}', [FrontendController::class, 'bestSellingProduct']);
     Route::get('product-stock', [FrontendController::class, 'stockProduct']);
     Route::get('product-stock-out', [FrontendController::class, 'stockOutProduct']);
     Route::get('all-products', [FrontendController::class, 'allProducts']);
     Route::get('single-product/{id}', [FrontendController::class, 'singleProduct']);
     Route::get('product/{productSlug}', [FrontendController::class, 'allsingleProduct']);
     Route::get('filter-byCategory/{id}', [FrontendController::class, 'filterByCategory']);
     Route::get('products-range', [FrontendController::class, 'filterByPriceRange']); //price range low to high
     Route::get('products-sort', [FrontendController::class, 'sortProducts']);
     Route::post('product/review/store', [CustomerController::class, 'review']);
Route::get('/products-by-tag/{tag}', [FrontendController::class, 'productsByTag']);

    //global search
    Route::get('global-search', [FrontendController::class, 'globalSearch']);
     
     
     Route::get('hotdeal-product', [FrontendController::class, 'hotdealproduct']);
     Route::get('homepage-product', [FrontendController::class, 'homepageproduct']);
     Route::get('footer-menu-left', [FrontendController::class, 'footermenuleft']);
     Route::get('footer-menu-right', [FrontendController::class, 'footermenuright']);
     Route::get('social-media', [FrontendController::class, 'socialmedia']);
     Route::get('contactinfo', [FrontendController::class, 'contactinfo']);
     Route::get('coupon', [FrontendController::class, 'coupon']);
     Route::get('siteinfo', [FrontendController::class, 'footerlogo']);
    Route::get('page/{slug}', [FrontendController::class, 'singlePage']);
    Route::post('user-message', [FrontendController::class, 'userMessage']);

    Route::get('image-review', [FrontendController::class, 'customerReview']);

    Route::post('customer-review/store', [FrontendController::class, 'productReviewStore'])->name('product_reviews.store');
    
    Route::get('notice', [FrontendController::class, 'notice']);
    //  Home Page Api End =================================
    
    Route::post('chack-out', [CustomerController::class, 'order_save']);
    
    Route::get('getDistrict', [FrontendController::class, 'getDistrict']);

    Route::get('districts/{district_name}', [FrontendController::class, 'districts']);

   Route::get('shipping-charge', [CustomerController::class, 'shippingCharge']);

    
    Route::post('customer/login', [CustomerController::class, 'login']);
    Route::post('customer/register', [CustomerController::class, 'register']);
    Route::post('customer/verify', [CustomerController::class, 'account_verify']);
    Route::post('customer/coupon', [CustomerController::class, 'customer_coupon']);
    
     
    Route::get('tag-manager/manage', [FrontendController::class, 'tagManager']);
    Route::get('customer/order-track/result', [CustomerController::class, 'order_track_result']);
    
    
    // offer
    
    Route::get('offers', [OfferController::class, 'offersApi']);
Route::get('/offers/{id}', [OfferController::class, 'offerProductsApi']);

Route::post('/products/filter', [ProductController::class, 'productFilters']);
// routes/api.php

Route::get('theme-colors', [ThemeColorController::class, 'index']);

// Incomplete Orders API - All endpoints PUBLIC (no authentication required)
Route::prefix('incomplete-orders')->group(function () {
    Route::post('/', [IncompleteOrderApiController::class, 'store']);
    Route::get('/', [IncompleteOrderApiController::class, 'index']);
    Route::get('/statistics', [IncompleteOrderApiController::class, 'statistics']);
    Route::get('/{id}', [IncompleteOrderApiController::class, 'show']);
    Route::post('/{id}/update-status', [IncompleteOrderApiController::class, 'updateStatus']);
    Route::post('/{id}/add-note', [IncompleteOrderApiController::class, 'addNote']);
    Route::post('/{id}/mark-contacted', [IncompleteOrderApiController::class, 'markAsContacted']);
    Route::delete('/{id}', [IncompleteOrderApiController::class, 'destroy']);
    Route::post('/bulk-delete', [IncompleteOrderApiController::class, 'bulkDelete']);
    Route::post('/bulk-update-status', [IncompleteOrderApiController::class, 'bulkUpdateStatus']);
});

// Feature Toggles API
Route::get('feature-toggles', [FeatureToggleController::class, 'getEnabledFeatures']);
Route::get('feature-toggles/{featureKey}', [FeatureToggleController::class, 'getFeature']);

 Route::get('customer/forgot-password', [CustomerController::class, 'forgot_password'])->name('customer.forgot.password');
    Route::post('customer/forgot-verify', [CustomerController::class, 'forgot_verify'])->name('customer.forgot.verify');
     Route::post('customer/forgot-password/store', [CustomerController::class, 'forgot_store'])->name('customer.forgot.store');



});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['namespace' => 'Api','prefix'=>'v1','middleware' =>'auth.jwt'], function(){
    
     Route::get('customer/profile', [CustomerController::class, 'profile']);
       
    Route::get('customer/login-check', [CustomerController::class, 'logincheck']);
    Route::post('customer/logout', [CustomerController::class, 'logout']);
    Route::post('customer/change-password', [CustomerController::class, 'change_password'])->name('customer.change_password');
    Route::post('customer/profile-update', [CustomerController::class, 'profile_update'])->name('profile_update');
    Route::get('customer/orders', [CustomerController::class, 'orders']);

});

Route::get('/updates/latest', [UpdateController::class, 'latest']);
// Route::post('/get-update-details', [UpdateClientController::class, 'getUpdateDetails'])->name('update.details');
Route::post('/updates/upload', [UpdateController::class, 'upload']);
Route::get('/updates/details', [UpdateController::class, 'details']);
Route::post('/updates/log-history', [UpdateController::class, 'logHistory']);
Route::post('/updates/validate-license', [UpdateController::class, 'validateLicense']);