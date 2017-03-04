<?php

namespace App;

use File;
use Route;

trait createRoutes
{
    protected $allRoutes = [];
    protected $localeCodes;
    protected $listFileFound;
    protected $listFileDir;

    /**
     * register routes for menu pages.
     *
     * @return [type] [description]
     */
    public function createRoutes()
    {
        $dir = config_path('simpleMenuTemp.php');
        $this->listFileDir = $dir;

        if (!File::exists($dir)) {
            $this->localeCodes = array_keys(\LaravelLocalization::getSupportedLocales());
            $this->listFileFound = true;

            foreach (cache('pages') as $page) {
                $this->generateRoute($page);
            }

            $this->saveRoutesToFile($this->allRoutes);
        } else {
            foreach (cache('pages') as $page) {
                $this->generateRoute($page);
            }
        }
    }

    /**
     * [getRoute description].
     *
     * @param [type] $code   [description]
     * @param [type] $name   [description]
     * @param mixed  $params
     *
     * @return [type] [description]
     */
    public function getUrl($name, $code, $params = null)
    {
        $url = config('simpleMenuTemp.'.$name.'.'.$code);

        if ($params) {
            foreach ($params as $key => $value) {
                // replace phs with params
                $url = preg_replace('/\{'.preg_quote($key).'(\?)?\}/', $value, $url);
            }
            // remove any extra phs to avoid weird chars in url
            $url = preg_replace('/\{.*\}/', '', $url);
        } else {
            $url = preg_replace('/\{.*\}/', '', $url);
        }

        return url($code.$url);
    }

    /**
     * [generateRoute description].
     *
     * @param [type] $page [description]
     *
     * @return [type] [description]
     */
    protected function generateRoute($page)
    {
        // page data
        $title = $page->title;
        $body = $page->body;
        $desc = trimfy($body);
        $template = $page->template;

        // route data
        $url = $page->url !== null ? $page->url : slugfy($title);
        $action = $page->action;
        $prefix = $action !== null ? $page->prefix : slugfy($page->prefix);
        $route = $prefix.'/'.$url;
        $routeName = slugfy($page->getTranslation('title', $this->defLocale));

        // middlewares
        $roles = 'role:'.implode(',', $page->roles()->pluck('name')->toArray());
        $permissions = 'perm:'.implode(',', $page->permissions()->pluck('name')->toArray());

        // make route
        $this->createRoute($routeName, $route, $action, $roles, $permissions, $template, $title, $body, $desc);

        // create route list
        if ($this->listFileFound) {
            $this->createRouteList($action, $page, $routeName);
        }
    }

    /**
     * [createRoute description].
     *
     * @param [type] $routeName   [description]
     * @param [type] $route       [description]
     * @param [type] $action      [description]
     * @param [type] $roles       [description]
     * @param [type] $permissions [description]
     * @param [type] $template    [description]
     * @param [type] $title       [description]
     * @param [type] $body        [description]
     * @param [type] $desc        [description]
     *
     * @return [type] [description]
     */
    protected function createRoute($routeName, $route, $action, $roles, $permissions, $template, $title, $body, $desc)
    {
        if ($action) {
            // dynamic
            Route::get($route, $action)->name($routeName)->middleware([$roles, $permissions]);
        } else {
            // static
            Route::get($route, function () use ($template, $title, $body, $desc) {
                return view("pages.{$template}")->with([
                    'title'    => $title,
                    'body'     => $body,
                    'desc'     => $desc,
                ]);
            })
            ->name($routeName)
            ->middleware([$roles, $permissions]);
        }
    }

    /**
     * [createRouteList description].
     *
     * @param [type] $action    [description]
     * @param [type] $page      [description]
     * @param [type] $routeName [description]
     *
     * @return [type] [description]
     */
    protected function createRouteList($action, $page, $routeName)
    {
        foreach ($this->localeCodes as $code) {
            $r_url = $page->getTranslation('url', $code) !== null ? $page->getTranslation('url', $code) : slugfy($page->getTranslation('title', $code));
            $r_prefix = $action !== null ? $page->getTranslation('prefix', $code) : slugfy($page->getTranslation('prefix', $code));
            $r_route = $r_prefix.'/'.$r_url;

            $this->allRoutes[$routeName][$code] = $r_route;
        }
    }

    /**
     * [saveRoutesToFile description].
     *
     * @param mixed $routes
     *
     * @return [type] [description]
     */
    protected function saveRoutesToFile($routes)
    {
        $data = "<?php\n\nreturn ".var_export($routes, true).';';
        $data = preg_replace('/\/+/', '/', $data);

        return File::put($this->listFileDir, $data);
    }
}
