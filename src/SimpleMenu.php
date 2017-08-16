<?php

namespace ctf0\SimpleMenu;

use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
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
    protected $cache;

    public function __construct()
    {
        $this->cache        = app()['cache'];
        $this->listFileDir  = config('simpleMenu.routeListPath');
        $this->localeCodes  = array_keys(LaravelLocalization::getSupportedLocales());

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
        $this->cache->tags('sm')->rememberForever('menus', function () {
            return Menu::with('pages')->get();
        });

        $this->cache->tags('sm')->rememberForever('pages', function () {
            return Page::get();
        });

        $this->cache->rememberForever('sm-users', function () {
            return app(config('simpleMenu.userModel'))->get();
        });
    }
}
