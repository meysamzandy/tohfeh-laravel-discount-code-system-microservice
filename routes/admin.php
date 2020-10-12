<?php


use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\DiscountCodeFeaturesController;
use Illuminate\Support\Facades\Route;


// prefix is admin
Route::get('/code', [DiscountCodeController::class, 'index']);

Route::post('/code', [DiscountCodeController::class, 'store']);

Route::put('/code/{id}', [DiscountCodeController::class, 'update']);


Route::get('/feature', [DiscountCodeFeaturesController::class, 'index']);

Route::post('/feature', [DiscountCodeFeaturesController::class, 'store']);

Route::delete('/feature/{id}', [DiscountCodeFeaturesController::class, 'destroy']);