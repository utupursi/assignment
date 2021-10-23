<?php

use Illuminate\Http\Request;
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

//Route::middleware('token')->group(function () {
//    Route::get('user', function () {
//        return response()->json(1);
//    })->name('user');
//    Route::post('create-access-token',[\App\Http\Controllers\AccessTokenController::class,'create']);
//    Route::post('delete-access-token',[\App\Http\Controllers\AccessTokenController::class,'delete']);
//});


Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/create-product', [\App\Http\Controllers\ProductController::class, 'addProduct']);
    Route::delete('/delete-product', [\App\Http\Controllers\ProductController::class, 'deleteProduct']);
    Route::post('/add-to-cart', [\App\Http\Controllers\CartController::class, 'addToCart']);
    Route::delete('/delete-from-cart', [\App\Http\Controllers\CartController::class, 'deleteFromCart']);

});


