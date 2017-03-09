<?php

namespace App\Providers;

use App\SimpleMenu;
use Illuminate\Support\ServiceProvider;

class SimpleMenuServiceProvidor extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $sm = new SimpleMenu();

        // cache
        $sm->createCache();

        // create routes
        $sm->createRoutes();

        // create menu
        $sm->createMenus();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(\App\Providers\SimpleMenuServiceProvidor::class);
    }
}
