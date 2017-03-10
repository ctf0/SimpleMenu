<?php

namespace App;

use File;
use LaravelLocalization;
use Route;

trait RoutesTrait
{
    protected $allRoutes = [];
    protected $localeCodes;
    protected $listFileFound = true;

    /**
     * [createRoutes description].
     *
     * @return [type] [description]
     */
    public function createRoutes()
    {
        Route::group([
            'prefix'     => LaravelLocalization::setLocale(),
            'middleware' => ['web', 'localeSessionRedirect', 'localizationRedirect'],
            'namespace'  => 'App\Http\Controllers',
            ], function () {
                $this->utilCheck();
            }
        );
    }

    protected function utilCheck()
    {
        if (!File::exists($this->listFileDir)) {
            $this->localeCodes = array_keys(LaravelLocalization::getSupportedLocales());
            $this->listFileFound = false;

            $this->utilLoop();
            $this->saveRoutesListToFile($this->allRoutes);
        } else {
            $this->utilLoop();
        }
    }

    protected function utilLoop()
    {
        foreach (cache('pages') as $page) {
            $this->pageComp($page);
        }
    }

    /**
     * [pageComp description].
     *
     * @param [type] $page [description]
     *
     * @return [type] [description]
     */
    protected function pageComp($page)
    {
        // page data
        $title = $page->title;
        $body = $page->body;
        $desc = trimfy($body);
        $template = $page->template;
        $breadCrump = $page->getAncestors();

        // route data
        $url = $page->url;

        if (config('simpleMenu.useTitleForUrl') && is_null($page->url)) {
            $url = slugfy($title);
        }

        $action = $page->action;
        $prefix = $action !== null ? $page->prefix : slugfy($page->prefix);
        $route = "$prefix/$url";
        $routeName = $page->route_name;

        // middlewares
        $roles = 'role:'.implode(',', $page->roles()->pluck('name')->toArray());
        $permissions = 'perm:'.implode(',', $page->permissions()->pluck('name')->toArray());

        // make route
        $this->routeGen($routeName, $route, $action, $roles, $permissions, $template, $title, $body, $desc, $breadCrump);

        // create route list
        if (!$this->listFileFound) {
            $this->createRoutesList($action, $page, $routeName);
        }
    }

    /**
     * [routeGen description].
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
     * @param [type] $breadCrump  [description]
     *
     * @return [type] [description]
     */
    protected function routeGen($routeName, $route, $action, $roles, $permissions, $template, $title, $body, $desc, $breadCrump)
    {
        if ($action) {
            // dynamic
            Route::get($route, $action)->name($routeName)->middleware([$roles, $permissions]);
        } else {
            // static
            Route::get($route, function () use ($template, $title, $body, $desc, $breadCrump) {
                return view("pages.{$template}")->with([
                    'title'      => $title,
                    'body'       => $body,
                    'desc'       => $desc,
                    'breadCrump' => $breadCrump,
                ]);
            })
            ->name($routeName)
            ->middleware([$roles, $permissions]);
        }
    }

    /**
     * [createRoutesList description].
     *
     * @param [type] $action    [description]
     * @param [type] $page      [description]
     * @param [type] $routeName [description]
     *
     * @return [type] [description]
     */
    protected function createRoutesList($action, $page, $routeName)
    {
        foreach ($this->localeCodes as $code) {
            $url = $page->getTranslation('url', $code);

            if (config('simpleMenu.useTitleForUrl') && is_null($page->getTranslation('url', $code))) {
                $url = slugfy($page->getTranslation('title', $code))
            }

            $prefix = $action !== null ? $page->getTranslation('prefix', $code) : slugfy($page->getTranslation('prefix', $code));
            $route = "$prefix/$url";

            $this->allRoutes[$routeName][$code] = $route;
        }
    }

    /**
     * [saveRoutesListToFile description].
     *
     * @param mixed $routes
     *
     * @return [type] [description]
     */
    protected function saveRoutesListToFile($routes)
    {
        $data = "<?php\n\nreturn ".var_export($routes, true).';';
        $data = preg_replace('/\/+/', '/', $data);

        // array(...) to [...]
        $data = str_replace('array (', '[', $data);
        $data = str_replace(')', ']', $data);
        $data = preg_replace('/=>\s+\[/', '=> [', $data);

        return File::put($this->listFileDir, $data);
    }
}
