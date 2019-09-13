<?php

namespace ctf0\SimpleMenu;

use Illuminate\Support\ServiceProvider;
use ctf0\SimpleMenu\Commands\PackageSetup;
use ctf0\SimpleMenu\Observers\MenuObserver;
use ctf0\SimpleMenu\Observers\PageObserver;
use ctf0\SimpleMenu\Observers\UserObserver;
use ctf0\SimpleMenu\Middleware\RoleMiddleware;
use ctf0\SimpleMenu\Middleware\PermissionMiddleware;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;

class SimpleMenuServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->packagePublish();
        $this->observers();
        $this->macros();
        $this->viewComp();
        $this->command();
        $this->app['simplemenu'];
    }

    protected function packagePublish()
    {
        // config
        $this->publishes([
            __DIR__ . '/config' => config_path(),
        ], 'config');

        // migrations
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'migrations');

        // seeds
        $this->publishes([
            __DIR__ . '/database/seeds' => database_path('seeds'),
        ], 'seeds');

        // resources
        $this->publishes([
            __DIR__ . '/resources/assets' => resource_path('assets/vendor/SimpleMenu'),
        ], 'assets');

        // trans
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'SimpleMenu');
        $this->publishes([
            __DIR__ . '/resources/lang' => resource_path('lang/vendor/SimpleMenu'),
        ], 'trans');

        // views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'SimpleMenu');
        $this->publishes([
            __DIR__ . '/resources/views'      => resource_path('views/vendor/SimpleMenu'),
            __DIR__ . '/resources/pagination' => resource_path('views/vendor/pagination'),
        ], 'views');
    }

    /**
     * model events observers.
     *
     * @return [type] [description]
     */
    protected function observers()
    {
        $models = $this->app['config']->get('simpleMenu.models');

        if ($models) {
            $this->app->make($models['page'])->observe(PageObserver::class);
            $this->app->make($models['menu'])->observe(MenuObserver::class);
            $this->app->make($models['user'])->observe(UserObserver::class);
        }
    }

    /**
     * package macros.
     *
     * @return [type] [description]
     */
    protected function macros()
    {
        // same as "Route::is()" but better
        $this->app['url']->macro('is', function ($route_name, $params = null) {
            return $params
                ? request()->url() == route($route_name, $params)
                : request()->url() == route($route_name);
        });

        $this->app['url']->macro('has', function ($needle) {
            return str_contains($this->current(), $needle);
        });
    }

    /**
     * share var across views.
     *
     * @return [type] [description]
     */
    protected function viewComp()
    {
        view()->composer('SimpleMenu::admin.*', function ($view) {
            $view->with([
                'crud_prefix' => $this->app['config']->get('simpleMenu.crud_prefix'),
            ]);
        });
    }

    /**
     * package commands.
     *
     * @return [type] [description]
     */
    protected function command()
    {
        $this->commands([
            PackageSetup::class,
        ]);
    }

    /**
     * Register any package services.
     */
    public function register()
    {
        $this->app->singleton('simplemenu', function () {
            return new SimpleMenu();
        });

        $this->regPSP();
        $this->regPMW();
    }

    /**
     * packages service providers.
     *
     * @return [type] [description]
     */
    protected function regPSP()
    {
        $packagesSP = [
            \Baum\Providers\BaumServiceProvider::class,
            \ctf0\PackageChangeLog\PackageChangeLogServiceProvider::class,
        ];

        foreach ($packagesSP as $one) {
            $this->app->register($one);
        }
    }

    /**
     * packages middlewares.
     *
     * @return [type] [description]
     */
    protected function regPMW()
    {
        $this->app['router']->aliasMiddleware('perm', PermissionMiddleware::class);
        $this->app['router']->aliasMiddleware('role', RoleMiddleware::class);
        $this->app['router']->aliasMiddleware('localizationRedirect', LaravelLocalizationRedirectFilter::class);
        $this->app['router']->aliasMiddleware('localeSessionRedirect', LocaleSessionRedirect::class);
    }
}
