<?php

namespace ctf0\SimpleMenu\Traits;

use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait Ops
{
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
        $dir_name = dirname($dir);

        if (!app('files')->exists($dir_name)) {
            return app('files')->makeDirectory($dir_name, 0755, true);
        }
    }

    /**
     * package routes.
     *
     * @return [type] [description]
     */
    public function menuRoutes()
    {
        $prefix      = config('simpleMenu.crud_prefix');
        $controllers = config('simpleMenu.controllers');

        Route::group([
            'prefix'=> $prefix,
            'as'    => "$prefix.",
        ], function () use ($controllers) {
            /*                Home                */
            Route::get('/', $controllers['admin'] . '@index')->name('index');

            /*               Roles               */
            Route::resource('roles', $controllers['roles'], ['except'=>'show']);

            /*               Perms               */
            Route::resource('permissions', $controllers['permissions'], ['except'=>'show']);

            /*               Menus               */
            Route::post('menus/removechild', $controllers['menus'] . '@removeChild')->name('menus.removeChild');
            Route::post('menus/removepage/{id}', $controllers['menus'] . '@removePage')->name('menus.removePage');
            Route::get('menus/getmenupages/{id}', $controllers['menus'] . '@getMenuPages')->name('menus.getMenuPages');
            Route::resource('menus', $controllers['menus'], ['except'=>'show']);

            /*               Users               */
            Route::resource('users', $controllers['users'], ['except'=>'show']);

            /*               Pages               */
            Route::resource('pages', $controllers['pages'], ['except'=>'show']);
        });
    }
}
