<?php

namespace ctf0\SimpleMenu;

use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
use ctf0\SimpleMenu\Traits\MenusTrait;
use ctf0\SimpleMenu\Traits\NavigationTrait;
use ctf0\SimpleMenu\Traits\RoutesTrait;
use Illuminate\Support\Facades\Cache;

class SimpleMenu
{
    use RoutesTrait, MenusTrait, NavigationTrait;

    protected $listFileDir;

    public function __construct()
    {
        $this->listFileDir = config('simpleMenu.routeListPath');

        // cache
        $this->createCache();

        // create routes
        $this->createRoutes();

        // create menu
        $this->createMenus();
    }

    /**
     * [createCache description].
     *
     * @return [type] [description]
     */
    public function createCache()
    {
        // for creating the routes
        Cache::rememberForever('pages', function () {
            return Page::get();
        });

        // for creating the menu
        Cache::rememberForever('menus', function () {
            return Menu::get();
        });
    }
}
