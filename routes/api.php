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

Route::group([
    'prefix' => 'company'
], function(){    
    Route::middleware('auth:api')->post('register','Api\Company\RegisterController@create');
    Route::middleware('auth:api')->put('edit','Api\Company\EditController@update');
    Route::middleware('auth:api')->get('info','Api\Company\InfoController@index');
    Route::middleware('auth:api')->get('totalMonthPayments','Api\Company\InfoController@totalMonthPayment');
});

Route::group([
    'prefix' => 'manufacturer'
], function(){    
    Route::middleware('auth:api')->get('list','Api\Manufacturer\InfoController@list');
    Route::middleware('auth:api')->get('info/{id}','Api\Manufacturer\InfoController@info');
    Route::middleware('auth:api')->post('register','Api\Manufacturer\RegisterController@create');
    Route::middleware('auth:api')->put('edit/{id}','Api\Manufacturer\EditController@update');
    Route::middleware('auth:api')->delete('remove/{id}','Api\Manufacturer\RemoveController@delete');
});