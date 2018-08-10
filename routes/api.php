<?php

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

Route::post('/v1/products', 'ProductController@store');
Route::get('/v1/products', 'ProductController@index');
Route::get('/v1/product/{sku}', 'ProductController@show');

Route::get('/v1/collections', 'CollectionController@index');
Route::get('/v1/collection/{id}/products', 'ProductController@collectionProducts');
