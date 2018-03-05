<?php

namespace ctf0\SimpleMenu;

use ctf0\SimpleMenu\Traits\Ops;
use ctf0\SimpleMenu\Traits\MenusTrait;
use ctf0\SimpleMenu\Traits\RoutesTrait;
use ctf0\SimpleMenu\Traits\NavigationTrait;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SimpleMenu
{
    use RoutesTrait, MenusTrait, NavigationTrait, Ops;

    protected $listFileDir;
    protected $localeCodes;
    protected $cache;

    public function __construct()
    {
        $this->cache        = app('cache');
        $this->listFileDir  = config('simpleMenu.routeListPath');
        $this->localeCodes  = array_keys(LaravelLocalization::getSupportedLocales());

        if ($this->listFileDir !== '') {
            static::create_LFD($this->listFileDir);
        }

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
