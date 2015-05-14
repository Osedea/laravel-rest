<?php

namespace Osedea\LaravelRest;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class LaravelRestServiceProvider extends ServiceProvider
{
    /**
     * Initialises the service provider.
     */
    public function boot()
    {
        $this->publishes([
            implode(DIRECTORY_SEPARATOR, [__DIR__, 'config', 'api.php']) => config_path('api.php')
        ]);

        $this->setupRoutes($this->app->router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Osedea\LaravelRest\Http\Controllers'], function ($router)
        {
            require implode(DIRECTORY_SEPARATOR, [__DIR__, 'Http', 'routes.php']);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Nothing to see here!
    }
}
