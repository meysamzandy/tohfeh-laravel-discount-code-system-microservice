<?php

use App\Http\Controllers\CodeProcessing;
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\DiscountCodeFeaturesController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::get('code/{discountCode:code}', [CodeProcessing::class, 'code']);

Route::group(['prefix' => 'admin', 'middleware' => 'CheckToken'], function() {

    Route::get('/code', [DiscountCodeController::class, 'index']);

    Route::post('/code', [DiscountCodeController::class, 'store']);

    Route::patch('/code/{id}', [DiscountCodeController::class, 'update']);


    Route::get('/feature', [DiscountCodeFeaturesController::class, 'index']);

    Route::post('/feature', [DiscountCodeFeaturesController::class, 'store']);

    Route::patch('/feature/{id}', [DiscountCodeFeaturesController::class, 'update']);

    Route::delete('/feature/{id}', [DiscountCodeFeaturesController::class, 'destroy']);


});