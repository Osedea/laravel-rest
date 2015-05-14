<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Namespace
    |--------------------------------------------------------------------------
    |
    | The current app namespace. This is used to automatically find commands
    | and events based on route query.
    |
    */

    'app_namespace' => 'App',

    /*
    |--------------------------------------------------------------------------
    | API Mapping
    |--------------------------------------------------------------------------
    |
    | Here we map routes to models. Model must exist under App\Models
    | namespace.
    |
    */

    'mapping' => [
        'users' => 'User'
    ],

    /*
    |--------------------------------------------------------------------------
    | API Prefix
    |--------------------------------------------------------------------------
    |
    | The prefix used by the API. If not present, it will default to `api`.
    |
    */

    'prefix' => 'api/v1'

];
