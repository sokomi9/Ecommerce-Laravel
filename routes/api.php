<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::group(['middleware' => 'api','prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('index', [BrandsController::class, 'index']);
Route::get('show/{id}', [BrandsController::class, 'show']);
Route::post('store', [BrandsController::class, 'store']);
Route::put('update_brand/{id}', [BrandsController::class, 'update_brand']);
Route::delete('delete/{id}', [BrandsController::class, 'delete']);
