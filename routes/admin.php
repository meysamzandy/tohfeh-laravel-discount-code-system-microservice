<?php


use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\DiscountCodeFeaturesController;
use App\Http\Controllers\DiscountCodeGroupController;
use App\Http\Controllers\SuccessJobsController;
use Illuminate\Support\Facades\Route;


// prefix is admin

//group
Route::get('/group', [DiscountCodeGroupController::class, 'index']);

Route::delete('/group/{id}', [DiscountCodeGroupController::class, 'destroy']);

//code
Route::get('/code', [DiscountCodeController::class, 'index']);

Route::post('/code', [DiscountCodeController::class, 'store']);

Route::put('/code/{id}', [DiscountCodeController::class, 'update']);

Route::delete('/code/{id}', [DiscountCodeController::class, 'destroy']);

//feature
Route::get('/feature', [DiscountCodeFeaturesController::class, 'index']);

Route::post('/feature', [DiscountCodeFeaturesController::class, 'store']);

Route::delete('/feature/{id}', [DiscountCodeFeaturesController::class, 'destroy']);

Route::get('/jobs', [SuccessJobsController::class, 'index']);