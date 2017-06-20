<?php

namespace ctf0\SimpleMenu;

use ctf0\SimpleMenu\Traits\MenusTrait;
use ctf0\SimpleMenu\Traits\NavigationTrait;
use ctf0\SimpleMenu\Traits\RoutesTrait;

class SimpleMenu
{
    use RoutesTrait, MenusTrait, NavigationTrait;

    protected $listFileDir;

    public function __construct()
    {
        $this->listFileDir = config('simpleMenu.routeListPath');

        // create routes
        $this->createRoutes();

        // create menu
        $this->createMenus();
    }
}
