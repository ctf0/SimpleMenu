<?php

namespace ctf0\SimpleMenu;

use ctf0\SimpleMenu\Middleware\PermissionMiddleware;
use ctf0\SimpleMenu\Middleware\RoleMiddleware;
use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
use ctf0\SimpleMenu\Observers\MenuObserver;
use ctf0\SimpleMenu\Observers\PageObserver;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

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
            __DIR__.'/config' => config_path(),
        ], 'config');

        // migrations
        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations'),
        ], 'migrations');

        // seeds
        $this->publishes([
            __DIR__.'/database/seeds/' => database_path('seeds'),
        ], 'seeds');

        // resources
        $this->publishes([
            __DIR__.'/resources/assets' => resource_path('assets/vendor/SimpleMenu'),
        ], 'assets');

        // trans
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'SimpleMenu');
        $this->publishes([
            __DIR__.'/resources/lang' => resource_path('lang/vendor/SimpleMenu'),
        ], 'trans');

        // views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'SimpleMenu');
        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/SimpleMenu'),
        ], 'views');

        // routes
        $this->publishes([
            __DIR__.'/routes' => base_path('routes'),
        ], 'routes');

        $this->observers();
        $this->macros();

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

        $this->regPSP();
        $this->regPA();
        $this->regPMW();
    }

    /**
     * model events observers.
     *
     * @return [type] [description]
     */
    protected function observers()
    {
        Page::observe(PageObserver::class);
        Menu::observe(MenuObserver::class);
    }

    protected function macros()
    {
        URL::macro('has', function ($needle) {
            return str_contains($this->current(), $needle);
        });
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
