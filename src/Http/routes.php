<?php

use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| Package Routes
|--------------------------------------------------------------------------
|
*/
$options = [
    'prefix' => Config::get('api.prefix', 'api'),
];

if (Config::has('api.middleware')) {
    $options['middleware'] = Config::get('api.middleware');
}

Route::group($options, function () {
    // These routes are using the api.php config file to map {resource} and {relation} to Models
    Route::get('/{resource}', 'DefaultRestController@index');
    Route::get('/{resource}/{id}', 'DefaultRestController@show');
    Route::post('/{resource}', 'DefaultRestController@store');
    Route::put('/{resource}/{id}', 'DefaultRestController@update');
    Route::delete('/{resource}/{id}', 'DefaultRestController@destroy');
    Route::get('/{resource}/{id}/{relation}', 'DefaultRestController@indexRelation');
});
