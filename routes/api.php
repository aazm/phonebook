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

Route::group(['middleware' => 'throttle:360,1'], function() {

    Route::apiResource('records', 'RecordsController');

    Route::post('exchange', 'ExchangeController@import');

    Route::get('meta', 'MetaController@show');

});
