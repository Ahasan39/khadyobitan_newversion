<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FrontendController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\UpdateController;
use App\Http\Controllers\UpdateClientController;

Route::group(['namespace' => 'Api','prefix'=>'v1','middleware' => 'api'], function(){
    
     Route::get('app-config', [FrontendController::class, 'appconfig']);
     Route::get('slider', [FrontendController::class, 'slider']);
     Route::get('categories', [FrontendController::class, 'category']);
     Route::get('hotdeal-product', [FrontendController::class, 'hotdealproduct']);
     Route::get('homepage-product', [FrontendController::class, 'homepageproduct']);
     Route::get('footer-menu-left', [FrontendController::class, 'footermenuleft']);
     Route::get('footer-menu-right', [FrontendController::class, 'footermenuright']);
     Route::get('social-media', [FrontendController::class, 'socialmedia']);
     Route::get('contactinfo', [FrontendController::class, 'contactinfo']);
     
    //  Home Page Api End =================================
    
    Route::get('category/{id}', [FrontendController::class, 'catproduct']);
    
    
    Route::post('customer/login', [CustomerController::class, 'login']);
    Route::post('customer/store', [CustomerController::class, 'register']);
    Route::post('customer/verify', [CustomerController::class, 'account_verify']);
    
    

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




