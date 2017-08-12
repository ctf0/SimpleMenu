<?php

namespace ctf0\SimpleMenu;

use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
use Illuminate\Support\Facades\Cache;
use ctf0\SimpleMenu\Traits\MenusTrait;
use ctf0\SimpleMenu\Traits\RoutesTrait;
use ctf0\SimpleMenu\Traits\NavigationTrait;
use ctf0\SimpleMenu\Traits\PackageRoutesTrait;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SimpleMenu
{
    use RoutesTrait, MenusTrait, NavigationTrait, PackageRoutesTrait;

    protected $listFileDir;
    protected $localeCodes;

    public function __construct()
    {
        $this->listFileDir = config('simpleMenu.routeListPath');
        $this->localeCodes = array_keys(LaravelLocalization::getSupportedLocales());

        if (!app()->runningInConsole()) {
            // create caches
            $this->createCaches();

            // create routes
            $this->createRoutes();

            // create menu
            $this->createMenus();
        }
    }

    public function AppLocales()
    {
        return $this->localeCodes;
    }

    protected function getCrntLocale()
    {
        return LaravelLocalization::getCurrentLocale();
    }

    protected function createCaches()
    {
        Cache::rememberForever('sm-menus', function () {
            return Menu::with('pages')->get();
        });

        Cache::rememberForever('sm-pages', function () {
            return Page::get();
        });

        Cache::rememberForever('sm-users', function () {
            return app(config('simpleMenu.userModel'))->get();
        });
    }
}
