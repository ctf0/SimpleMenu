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
        // cache
        (new SimpleMenu())->createCache();

        // create menu
        (new SimpleMenu())->createMenus();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(\App\Providers\SimpleMenuServiceProvidor::class);
    }
}
