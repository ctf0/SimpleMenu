<?php

namespace ctf0\SimpleMenu\Traits;

trait PackageRoutes
{
    /**
     * package routes.
     *
     * @return [type] [description]
     */
    public function menuRoutes()
    {
        $prefix      = config('simpleMenu.crud_prefix');
        $controllers = config('simpleMenu.controllers');

        app('router')->group([
            'prefix'=> $prefix,
        ], function () use ($prefix, $controllers) {
            /*                Home                */
            if (isset($controllers['admin'])) {
                app('router')->get('/', $controllers['admin'])->name($prefix);
            }

            /*                Everything Else                */
            app('router')->group([
                'as'=> "$prefix.",
            ], function () use ($controllers) {
                /*               Roles               */
                app('router')->resource('roles', $controllers['roles'], ['except'=>'show']);
                app('router')->post('roles/remove-multi', $controllers['roles'] . '@destroyMulti')->name('roles.destroy_multi');

                /*               Perms               */
                app('router')->resource('permissions', $controllers['permissions'], ['except'=>'show']);
                app('router')->post('permissions/remove-multi', $controllers['permissions'] . '@destroyMulti')->name('permissions.destroy_multi');

                /*               Menus               */
                app('router')->post('menus/removechild', $controllers['menus'] . '@removeChild')->name('menus.removeChild');
                app('router')->post('menus/removepage/{id}', $controllers['menus'] . '@removePage')->name('menus.removePage');
                app('router')->get('menus/getmenupages/{id}', $controllers['menus'] . '@getMenuPages')->name('menus.getMenuPages');
                app('router')->resource('menus', $controllers['menus'], ['except'=>'show']);
                app('router')->post('menus/remove-multi', $controllers['menus'] . '@destroyMulti')->name('menus.destroy_multi');

                /*               Users               */
                app('router')->resource('users', $controllers['users'], ['except'=>'show']);
                app('router')->post('users/remove-multi', $controllers['users'] . '@destroyMulti')->name('users.destroy_multi');

                /*               Pages               */
                app('router')->resource('pages', $controllers['pages'], ['except'=>'show']);
                app('router')->post('pages/remove-multi', $controllers['pages'] . '@destroyMulti')->name('pages.destroy_multi');
                app('router')->put('pages/{id}/restore', $controllers['pages'] . '@restore')->name('pages.restore');
                app('router')->delete('pages/{id}/remove-force', $controllers['pages'] . '@forceDelete')->name('pages.destroy_force');
            });
        });
    }
}
