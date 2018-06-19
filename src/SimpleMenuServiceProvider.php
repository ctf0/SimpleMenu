<?php

namespace ctf0\SimpleMenu;

use Illuminate\Support\ServiceProvider;
use ctf0\SimpleMenu\Observers\MenuObserver;
use ctf0\SimpleMenu\Observers\PageObserver;
use ctf0\SimpleMenu\Observers\UserObserver;
use ctf0\SimpleMenu\Middleware\RoleMiddleware;
use ctf0\SimpleMenu\Middleware\PermissionMiddleware;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;

class SimpleMenuServiceProvider extends ServiceProvider
{
    protected $file;

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->file = $this->app['files'];

        $this->packagePublish();
        $this->observers();
        $this->macros();
        $this->viewComp();
        $this->app['simplemenu'];

        // append extra data
        if (!$this->app['cache']->store('file')->has('ct-sm')) {
            $this->autoReg();
        }
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
            __DIR__ . '/resources/views' => resource_path('views/vendor/SimpleMenu'),
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
            $this->app->make(array_get($models, 'page'))->observe(PageObserver::class);
            $this->app->make(array_get($models, 'menu'))->observe(MenuObserver::class);
            $this->app->make(array_get($models, 'user'))->observe(UserObserver::class);
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
     * [autoReg description].
     *
     * @return [type] [description]
     */
    protected function autoReg()
    {
        // routes
        $route_file = base_path('routes/web.php');
        $search     = 'SimpleMenu';

        if ($this->checkExist($route_file, $search)) {
            $data = "\n// SimpleMenu\nSimpleMenu::menuRoutes();";

            $this->file->append($route_file, $data);
        }

        // mix
        $mix_file = base_path('webpack.mix.js');
        $search   = 'SimpleMenu';

        if ($this->checkExist($mix_file, $search)) {
            $data = "\n// SimpleMenu\nmix.sass('resources/assets/vendor/SimpleMenu/sass/style.scss', 'public/assets/vendor/SimpleMenu/style.css').version();";

            $this->file->append($mix_file, $data);
        }

        // run check once
        $this->app['cache']->store('file')->rememberForever('ct-sm', function () {
            return 'added';
        });
    }

    /**
     * [checkExist description].
     *
     * @param [type] $file   [description]
     * @param [type] $search [description]
     *
     * @return [type] [description]
     */
    protected function checkExist($file, $search)
    {
        return $this->file->exists($file) && !str_contains($this->file->get($file), $search);
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
