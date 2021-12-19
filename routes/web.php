<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('category', 'Admin\CategoryController');
Route::get('category-update/{id}/{status}', 'Admin\CategoryController@category_status_update')->name('category.status.update');

Route::resource('product', 'Admin\ProductController');
Route::get('product-update/{id}/{status}', 'Admin\ProductController@product_status_update')->name('product.status.update');
