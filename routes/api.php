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

Route::get('/articles', [\App\Http\Controllers\ArticleController::class, 'getArticle']);
Route::get('/articles/{id}/comments', [\App\Http\Controllers\ArticleController::class, 'getArticleComments']);
Route::get('/tags', [\App\Http\Controllers\TagController::class, 'getTags']);
Route::get('/tags/{id}/articles', [\App\Http\Controllers\TagController::class, 'getTagArticles']);

Route::post('/create-product',[\App\Http\Controllers\ProductController::class,'addProduct']);
Route::delete('/delete-product',[\App\Http\Controllers\ProductController::class,'deleteProduct']);

