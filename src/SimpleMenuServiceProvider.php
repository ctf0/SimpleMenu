<?php

namespace ctf0\SimpleMenu;

use ctf0\SimpleMenu\Middleware\PermissionMiddleware;
use ctf0\SimpleMenu\Middleware\RoleMiddleware;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

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
        $this->app['router']->aliasMiddleware('localizationRedirect', \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class);
        $this->app['router']->aliasMiddleware('localeSessionRedirect', \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class);
    }
}
