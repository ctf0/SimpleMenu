<?php

namespace ctf0\SimpleMenu;

use ctf0\SimpleMenu\Middleware\PermissionMiddleware;
use ctf0\SimpleMenu\Middleware\RoleMiddleware;
use Illuminate\Support\ServiceProvider;

class SimpleMenuServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        // config
        $this->publishes([
            __DIR__.'/config' => config_path(),
        ], 'config');

        // models
        $this->publishes([
            __DIR__.'/Models' => app_path(),
        ], 'models');

        // migrations
        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations'),
        ], 'migrations');

        // seeds
        $this->publishes([
            __DIR__.'/database/seeds/' => database_path('seeds'),
        ], 'seeds');

        // views
        $this->loadViewsFrom(__DIR__.'/views', 'SimpleMenu');
        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/SimpleMenu'),
        ], 'views');

        $this->app['simplemenu'];
    }

    /**
     * Register any package services.
     */
    public function register()
    {
        $this->app->singleton('simplemenu', function () {
            return new SimpleMenu();
        });

        $this->app->alias('simplemenu', SimpleMenu::class);

        $this->regMW();
    }

    /**
     * register app middlewares.
     *
     * @return [type] [description]
     */
    protected function regMW()
    {
        $this->app['router']->aliasMiddleware('perm', PermissionMiddleware::class);
        $this->app['router']->aliasMiddleware('role', RoleMiddleware::class);
    }
}
