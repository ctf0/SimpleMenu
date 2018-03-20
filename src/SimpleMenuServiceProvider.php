<?php

namespace ctf0\SimpleMenu;

use Illuminate\Support\Facades\URL;
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
    protected $packagesSP = [
        \Baum\Providers\BaumServiceProvider::class,
        \ctf0\PackageChangeLog\PackageChangeLogServiceProvider::class,
    ];

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->file = app('files');

        $this->packagePublish();

        // append extra data
        if (!app('cache')->store('file')->has('ct-sm')) {
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

        $this->observers();
        $this->macros();
        $this->viewComp();

        $this->app['simplemenu'];
    }

    /**
     * model events observers.
     *
     * @return [type] [description]
     */
    protected function observers()
    {
        if (!app()->runningInConsole()) {
            app(config('simpleMenu.models.page'))->observe(PageObserver::class);
            app(config('simpleMenu.models.menu'))->observe(MenuObserver::class);
            app(config('simpleMenu.models.user'))->observe(UserObserver::class);
        }
    }

    /**
     * package macros.
     *
     * @return [type] [description]
     */
    protected function macros()
    {
        // alias to "Route::is()" but with support for params
        URL::macro('is', function ($route_name, $params = null) {
            if ($params) {
                return request()->url() == route($route_name, $params);
            }

            return request()->url() == route($route_name);
        });

        URL::macro('has', function ($needle) {
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
                'crud_prefix' => config('simpleMenu.crud_prefix'),
            ]);
        });
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
        $this->regPA();
        $this->regPMW();
    }

    /**
     * packages service providers.
     *
     * @return [type] [description]
     */
    protected function regPSP()
    {
        foreach ($this->packagesSP as $one) {
            $this->app->register($one);
        }
    }

    /**
     * packages aliases.
     *
     * @return [type] [description]
     */
    protected function regPA()
    {
        $this->app->alias('simplemenu', SimpleMenu::class);
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
        app('cache')->store('file')->rememberForever('ct-sm', function () {
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
}
