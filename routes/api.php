<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/company/list','Api\CompanyController@list');
Route::middleware('auth:api')->post('/company/create','Api\CompanyController@create');
Route::middleware('auth:api')->put('/company/update/{id}','Api\CompanyController@update');
Route::middleware('auth:api')->get('/company/info/{id}','Api\CompanyController@info');

Route::middleware('auth:api')->get('/manufacturer/list','Api\ManufacturerController@list');
Route::middleware('auth:api')->post('/manufacturer/create','Api\ManufacturerController@create');
Route::middleware('auth:api')->put('/manufacturer/update/{id}','Api\ManufacturerController@update');
Route::middleware('auth:api')->get('/manufacturer/info/{id}','Api\ManufacturerController@get');

Route::middleware('auth:api')->post('/users/create','Api\UserController@create');
