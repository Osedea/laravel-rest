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
    | Models Namespace
    |--------------------------------------------------------------------------
    |
    | The Eloquent models namespace. This has to be the namespace from the
    | root of the project.
    | If not specified, then `app_namespace` will be used instead.
    |
    */

    'models_namespace' => 'App',

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

    'prefix' => 'api/v1',

    /*
    |--------------------------------------------------------------------------
    | API Middleware
    |--------------------------------------------------------------------------
    |
    | If your API routes need to use a middleware or filter, specify them here.
    | However it will apply for all of them.
    |
    */

    'middleware' => [],
];
