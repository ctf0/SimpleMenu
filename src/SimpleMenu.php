<?php

namespace ctf0\SimpleMenu;

use ctf0\SimpleMenu\Traits\Ops;
use ctf0\SimpleMenu\Traits\Menus;
use ctf0\SimpleMenu\Traits\Routes;
use ctf0\SimpleMenu\Traits\Navigation;
use ctf0\SimpleMenu\Traits\PackageRoutes;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SimpleMenu
{
    use Routes,
        Menus,
        Navigation,
        Ops,
        PackageRoutes;

    protected $listFileDir;
    protected $localeCodes;
    protected $cache;

    public function __construct()
    {
        $this->cache        = app('cache');
        $this->listFileDir  = config('simpleMenu.routeListPath');
        $this->localeCodes  = array_keys(LaravelLocalization::getSupportedLocales());

        if ($this->listFileDir) {
            static::create_LFD($this->listFileDir);

            if (!app()->runningInConsole()) {
                // create caches
                $this->createCaches();

                // create routes
                $this->createRoutes();

                // create menu
                $this->createMenus();
            }
        }
    }
}
