<?php

namespace App\Providers;

use App\Http\Controllers\MenuController;
use App\Http\Models\Menu;
use App\Http\Models\Page;
use Cache;
use Illuminate\Support\ServiceProvider;

class SimpleMenuServiceProvidor extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Cache::rememberForever('menus', function () {
            return Menu::get();
        });

        Cache::rememberForever('pages', function () {
            return Page::get();
        });

        new MenuController();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(\App\Providers\SimpleMenuServiceProvidor::class);
    }
}
