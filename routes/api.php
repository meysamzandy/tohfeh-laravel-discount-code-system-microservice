<?php

use App\Http\Controllers\CodeCallBack;
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

Route::post('code/callback', [CodeCallBack::class, 'callback']);

