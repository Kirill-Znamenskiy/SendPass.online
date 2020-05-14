<?php

namespace App\ServiceProviders;

use App\Custom\CustomUrlGenerator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCustomUrlGenerator();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->afterResolving('url', function (CustomUrlGenerator $url) {
            $url->formatPathUsing(function($path) {
                return rtrim($path,'/').'/';
            });
        });
    }


    protected function registerCustomUrlGenerator()
    {
        // this is copy-paste from \Illuminate\Routing\RoutingServiceProvider@registerUrlGenerator
        $this->app->singleton('url', function ($app) {
            /** @var Application $app */

            $routes = $app['router']->getRoutes();

            // The URL generator needs the route collection that exists on the router.
            // Keep in mind this is an object, so we're passing by references here
            // and all the registered routes will be available to the generator.
            $app->instance('routes', $routes);

            // this is copy-paste from \Illuminate\Routing\RoutingServiceProvider@requestRebinder
            $requestRebinder = function ($app, $request) {
                $app['url']->setRequest($request);
            };

            return new CustomUrlGenerator($routes
                , $app->rebinding('request', $requestRebinder)
                , $app['config']['app.asset_url']
            );
        });

    }

}
