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
        ], function () use ($prefix, $controllers) {
            /*                Home                */
            if (isset($controllers['admin'])) {
                Route::get('/', $controllers['admin'])->name($prefix);
            }

            /*                Everything Else                */
            Route::group([
                'as'=> "$prefix.",
            ], function () use ($controllers) {
                /*               Roles               */
                Route::resource('roles', $controllers['roles'], ['except'=>'show']);
                Route::post('roles/remove-multi', $controllers['roles'] . '@destroyMulti')->name('roles.destroy_multi');

                /*               Perms               */
                Route::resource('permissions', $controllers['permissions'], ['except'=>'show']);
                Route::post('permissions/remove-multi', $controllers['permissions'] . '@destroyMulti')->name('permissions.destroy_multi');

                /*               Menus               */
                Route::post('menus/removechild', $controllers['menus'] . '@removeChild')->name('menus.removeChild');
                Route::post('menus/removepage/{id}', $controllers['menus'] . '@removePage')->name('menus.removePage');
                Route::get('menus/getmenupages/{id}', $controllers['menus'] . '@getMenuPages')->name('menus.getMenuPages');
                Route::resource('menus', $controllers['menus'], ['except'=>'show']);
                Route::post('menus/remove-multi', $controllers['menus'] . '@destroyMulti')->name('menus.destroy_multi');

                /*               Users               */
                Route::resource('users', $controllers['users'], ['except'=>'show']);
                Route::post('users/remove-multi', $controllers['users'] . '@destroyMulti')->name('users.destroy_multi');

                /*               Pages               */
                Route::resource('pages', $controllers['pages'], ['except'=>'show']);
                Route::post('pages/remove-multi', $controllers['pages'] . '@destroyMulti')->name('pages.destroy_multi');
            });
        });
    }
}
