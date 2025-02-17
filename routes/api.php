<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::group(['middleware' => 'api','prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
//Brands CRUD
Route::controller(BrandsController::class)->group(function(){
    Route::get('index', 'index');
    Route::get('show/{id}', 'show');
    Route::post('store', 'store');
    Route::put('update_brand/{id}', 'update_brand');
    Route::delete('delete/{id}', 'delete_brand');
});
//Categories CRUD
Route::controller(CategoryController::class)->group(function(){
    Route::get('index', 'index');
    Route::get('show/{id}', 'show');
    Route::post('store', 'store');
    Route::put('update_category/{id}', 'update_category');
    Route::delete('delete/{id}', 'delete_category');
});
