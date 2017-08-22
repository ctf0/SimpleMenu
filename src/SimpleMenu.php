<?php

namespace ctf0\SimpleMenu;

use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
use Illuminate\Support\Facades\File;
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
        $this->cache        = app('cache');
        $this->listFileDir  = config('simpleMenu.routeListPath');
        $this->localeCodes  = array_keys(LaravelLocalization::getSupportedLocales());

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

    /**
     * locales.
     */
    public function AppLocales()
    {
        return $this->localeCodes;
    }

    protected function getCrntLocale()
    {
        return LaravelLocalization::getCurrentLocale();
    }

    /**
     * cacheing.
     *
     * @return [type] [description]
     */
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

    /**
     * route list dir.
     *
     * @param mixed $dir
     */
    protected static function create_LFD($dir)
    {
        $file_name = substr(strrchr($dir, '/'), 1);
        $dir_only  = str_replace($file_name, '', $dir);

        if (!File::exists($dir_only)) {
            return File::makeDirectory($dir_only, 0755, true);
        }
    }
}
