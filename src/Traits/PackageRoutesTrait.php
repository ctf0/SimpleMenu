<?php

namespace ctf0\SimpleMenu\Traits;

use Illuminate\Support\Facades\Route;

trait PackageRoutesTrait
{
    /**
     * package routes.
     *
     * @return [type] [description]
     */
    public function menuRoutes()
    {
        $prefix = config('simpleMenu.crud_prefix');

        Route::group([
            'prefix'=> $prefix,
            'as'    => "$prefix.",
        ], function () {
            /*                Home                */
            Route::get('/', '\ctf0\SimpleMenu\Controllers\Admin\AdminController@index')->name('index');

            /*               Roles               */
            Route::resource('roles', '\ctf0\SimpleMenu\Controllers\Admin\RolesController');

            /*               Perms               */
            Route::resource('permissions', '\ctf0\SimpleMenu\Controllers\Admin\PermissionsController');

            /*               Menus               */
            Route::post('menus/removechild', '\ctf0\SimpleMenu\Controllers\Admin\MenusController@removeChild')->name('menus.removeChild');
            Route::post('menus/removepage/{id}', '\ctf0\SimpleMenu\Controllers\Admin\MenusController@removePage')->name('menus.removePage');
            Route::get('menus/getmenupages/{id}', '\ctf0\SimpleMenu\Controllers\Admin\MenusController@getMenuPages')->name('menus.getMenuPages');
            Route::resource('menus', '\ctf0\SimpleMenu\Controllers\Admin\MenusController', ['except' => 'show']);

            /*               Users               */
            Route::resource('users', '\ctf0\SimpleMenu\Controllers\Admin\UsersController');

            /*               Pages               */
            Route::resource('pages', '\ctf0\SimpleMenu\Controllers\Admin\PagesController');
        });
    }
}
