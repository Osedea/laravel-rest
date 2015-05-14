<?php

use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| Package Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => Config::get('api.prefix', 'api')], function () {
    // These routes are using the api.php config file to map {resource} and {relation} to Models
    Route::get('/{resource}', 'DefaultRestController@index');
    Route::get('/{resource}/{id}', 'DefaultRestController@show');
    Route::post('/{resource}', 'DefaultRestController@store');
    Route::put('/{resource}/{id}', 'DefaultRestController@update');
    Route::delete('/{resource}/{id}', 'DefaultRestController@destroy');
    Route::get('/{resource}/{id}/{relation}', 'DefaultRestController@indexRelation');
});
