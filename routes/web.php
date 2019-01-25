<?php

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

Route::group([
    'prefix' => 'admin'
], function(){
    Route::get('/settings', 'SettingsController@index')->name('settings');
});

Route::group([
    'prefix' => 'client'
], function(){
    Route::get('/authorize', 'Passport\CallbackController@authorizationCode')->name('authorize');
    Route::get('/callback', 'Passport\CallbackController@index')->name('callback');
    Route::post('/token-info', 'Passport\CallbackController@getToken')->name('getToken');
});

Route::get('/home', 'HomeController@index')->name('home');


Auth::routes();