<?php

namespace ctf0\SimpleMenu\Traits;

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
        $models = config('simpleMenu.models');

        $this->cache->tags('sm')->rememberForever('menus', function () use ($models) {
            return app(array_get($models, 'menu'))->with('pages')->get();
        });

        $this->cache->tags('sm')->rememberForever('pages', function () use ($models) {
            return app(array_get($models, 'page'))->withTrashed()->get();
        });

        $this->cache->rememberForever('sm-users', function () use ($models) {
            return app(array_get($models, 'user'))->with(['roles', 'permissions'])->get();
        });
    }

    /**
     * route list dir.
     *
     * @param mixed $dir
     */
    protected static function create_LFD($dir)
    {
        $files = app('files');

        if ($files->exists(config_path('simpleMenu.php'))) {
            $dir_name = dirname($dir);

            if (!$files->exists($dir_name)) {
                return $files->makeDirectory($dir_name, 0755, true);
            }
        }
    }
}
