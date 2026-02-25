<?php

use Illuminate\Support\Facades\Route;
use Modules\HomePageOne\Http\Controllers\HomePageOneController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('homepageones', HomePageOneController::class)->names('homepageone');
});
