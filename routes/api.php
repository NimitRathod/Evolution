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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// used in this project 
Route::POST('add_category', 'API\ApiMainController@add_category')->name('add_category');
Route::POST('edit_category', 'API\ApiMainController@edit_category')->name('edit_category');
Route::POST('get_category_list', 'API\ApiMainController@get_category_list')->name('get_category_list');

Route::POST('add_product', 'API\ApiMainController@add_product')->name('add_product');
Route::POST('edit_product', 'API\ApiMainController@edit_product')->name('edit_product');
Route::POST('get_product_list', 'API\ApiMainController@get_product_list')->name('get_product_list');
