<?php

namespace ctf0\SimpleMenu;

use ctf0\SimpleMenu\Traits\MenusTrait;
use ctf0\SimpleMenu\Traits\NavigationTrait;
use ctf0\SimpleMenu\Traits\RoutesTrait;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SimpleMenu
{
    use RoutesTrait, MenusTrait, NavigationTrait;

    protected $listFileDir;
    protected $localeCodes;

    public function __construct()
    {
        $this->listFileDir = config('simpleMenu.routeListPath');
        $this->localeCodes = array_keys(LaravelLocalization::getSupportedLocales());

        if (!app()->runningInConsole()) {
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
}
