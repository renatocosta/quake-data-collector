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
Route::group(['middleware' => ['auth:api', 'api'], 'prefix' => 'playersinformation'], function () {

    Route::post('/', 'PlayersInformationController@store');
    
    Route::group(['prefix' => 'reporting'], function () {
         Route::get('/', 'ReportingController@index');
         Route::get('/datasource/{id}', 'ReportingController@source');
    });

});