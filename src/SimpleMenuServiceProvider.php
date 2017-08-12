<?php

namespace ctf0\SimpleMenu;

use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use ctf0\SimpleMenu\Observers\UserObserver;
use ctf0\SimpleMenu\Middleware\RoleMiddleware;
use ctf0\SimpleMenu\Middleware\PermissionMiddleware;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;

class SimpleMenuServiceProvider extends ServiceProvider
{
    protected $packagesSP = [
        \Baum\Providers\BaumServiceProvider::class,
        \Spatie\Translatable\TranslatableServiceProvider::class,
        \Spatie\Permission\PermissionServiceProvider::class,
        \Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class,
    ];

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        // config
        $this->publishes([
            __DIR__ . '/config' => config_path(),
        ], 'config');

        // migrations
        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations'),
        ], 'migrations');

        // seeds
        $this->publishes([
            __DIR__ . '/database/seeds/' => database_path('seeds'),
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
            app(config('simpleMenu.userModel'))->observe(UserObserver::class);
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
    }

    /**
     * share var across views.
     *
     * @return [type] [description]
     */
    protected function viewComp()
    {
        view()->composer('SimpleMenu::admin.*', function ($view) {
            $view->with(['crud_prefix' => config('simpleMenu.crud_prefix')]);
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

        AliasLoader::getInstance()->alias('LaravelLocalization', 'Mcamara\LaravelLocalization\Facades\LaravelLocalization');
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
