<?php

namespace App\Providers;

use App\Http\Models\Menu;
use App\Http\Models\Page;
use App\SimpleMenu;
use Cache;
use Illuminate\Support\ServiceProvider;

class SimpleMenuServiceProvidor extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // for creating the routes
        Cache::rememberForever('pages', function () {
            return Page::get();
        });

        // for creating the menu
        Cache::rememberForever('menus', function () {
            return Menu::with('pages')->get();
        });

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
