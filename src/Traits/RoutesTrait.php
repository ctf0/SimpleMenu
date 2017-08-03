<?php

namespace ctf0\SimpleMenu\Traits;

use ctf0\SimpleMenu\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

trait RoutesTrait
{
    protected $allRoutes     = [];
    protected $listFileFound = true;

    public function createRoutes()
    {
        Route::group([
            'prefix'     => LaravelLocalization::setLocale(),
            'middleware' => [
                'web',
                LocaleSessionRedirect::class,
                LaravelLocalizationRedirectFilter::class,
            ],
        ], function () {
                $this->utilCheck();
            }
        );
    }

    protected function utilCheck()
    {
        if (!File::exists($this->listFileDir)) {
            $this->listFileFound = false;

            $this->utilLoop();
            $this->saveRoutesListToFile($this->allRoutes);
        } else {
            $this->utilLoop();
        }
    }

    /**
     * runs everytime you change the current local
     * so routes links gets dynamically changed without causing issues.
     *
     * @return [type] [description]
     */
    protected function utilLoop()
    {
        Cache::rememberForever('sm-pages', function () {
            return Page::get();
        });

        foreach (cache('sm-pages') as $page) {
            $this->pageComp($page);
        }
    }

    protected function pageComp($page)
    {
        // page data
        $title      = $page->title;
        $body       = $page->body;
        $desc       = $page->desc;
        $template   = $page->template;
        $breadCrumb = $page->getAncestors();

        // route data
        $url       = $page->url;
        $action    = $page->action;
        $prefix    = $page->prefix;
        $routeName = $page->route_name;

        // middlewares
        $roles       = 'role:'.implode(',', $page->roles()->pluck('name')->toArray());
        $permissions = 'perm:'.implode(',', $page->permissions()->pluck('name')->toArray());

        // make route
        $this->routeGen($routeName, $url, $prefix, $action, $roles, $permissions, $template, $title, $body, $desc, $breadCrumb);

        // create route list
        if (!$this->listFileFound) {
            $this->createRoutesList($action, $page, $routeName);
        }
    }

    protected function routeGen($routeName, $url, $prefix, $action, $roles, $permissions, $template, $title, $body, $desc, $breadCrumb)
    {
        if ($this->escapeEmptyRoute($url)) {
            return;
        }

        // cache the page so we can pass the page params to the controller@method
        Cache::rememberForever(LaravelLocalization::getCurrentLocale()."-$routeName", function () use ($template, $title, $body, $desc, $breadCrumb) {
            return compact('template', 'title', 'body', 'desc', 'breadCrumb');
        });

        $route = $this->getRouteUrl($url, $prefix);

        // dynamic
        if ($action) {
            Route::get($route)
                ->uses(config('simpleMenu.pagesControllerNS').'\\'.$action)
                ->name($routeName)
                ->middleware([$roles, $permissions]);
        }
        // static
        else {
            Route::get($route)
                ->uses('\ctf0\SimpleMenu\Controllers\DummyController@handle')
                ->name($routeName)
                ->middleware([$roles, $permissions]);
        }
    }

    protected function createRoutesList($action, $page, $routeName)
    {
        foreach ($this->AppLocales() as $code) {
            $url    = $page->getTranslationWithoutFallback('url', $code);
            $prefix = $page->getTranslationWithoutFallback('prefix', $code);

            if ($this->escapeEmptyRoute($url)) {
                continue;
            }

            $route = $this->getRouteUrl($url, $prefix);

            $this->allRoutes[$routeName][$code] = $route;
        }
    }

    protected function escapeEmptyRoute($url)
    {
        return empty($url);
    }

    protected function getRouteUrl($url, $prefix)
    {
        if (empty($prefix)) {
            $clear = $url;
        } else {
            $clear = "$prefix/$url";
        }

        return $this->clearExtraSlash($clear);
    }

    protected function saveRoutesListToFile($routes)
    {
        $data = "<?php\n\nreturn ".var_export($routes, true).';';
        $data = $this->clearExtraSlash($data);

        // array(...) to [...]
        $data = str_replace('array (', '[', $data);
        $data = str_replace(')', ']', $data);
        $data = preg_replace('/=>\s+\[/', '=> [', $data);

        return File::put($this->listFileDir, $data);
    }

    protected function clearExtraSlash($url)
    {
        return preg_replace('/\/+/', '/', $url);
    }
}
