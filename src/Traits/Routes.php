<?php

namespace ctf0\SimpleMenu\Traits;

use Illuminate\Support\Arr;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait Routes
{
    protected $allRoutes = [];
    protected $listFileFound = true;

    public function createRoutes()
    {
        app('router')->group([
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => [
                'web',
                'localeSessionRedirect',
                'localizationRedirect',
            ],
        ], function () {
            $this->utilCheck();
        });
    }

    protected function utilCheck()
    {
        if (!app('files')->exists($this->listFileDir)) {
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
        foreach ($this->cache->tags('sm')->get('pages') as $page) {
            // make route
            $this->routeGen($page);

            // create route list
            if (!$this->listFileFound) {
                $this->createRoutesList($page);
            }
        }
    }

    protected function routeGen($page)
    {
        if ($this->escapeEmptyRoute($page->url)) {
            return;
        }

        // route data
        $url = $page->url;
        $action = $page->action;
        $prefix = $page->prefix;
        $routeName = $page->route_name;

        // page data
        $title = $page->title;
        $body = $page->body;
        $desc = $page->desc;
        $meta = $page->meta;
        $cover = $page->cover;
        $template = $page->template;
        $breadCrumb = $page->getAncestors();

        // middlewares
        $middlewares = is_null($page->middlewares) ? null : preg_split('/[\s,]+/', $page->middlewares);
        $roles = empty($page->roles->pluck('name')->toArray()) ? null : 'role:' . implode(',', $page->roles->pluck('name')->toArray());
        $permissions = empty($page->permissions->pluck('name')->toArray()) ? null : 'perm:' . implode(',', $page->permissions->pluck('name')->toArray());

        // cache the page so we can pass the page params to the controller@method
        $compact = compact('template', 'title', 'body', 'desc', 'meta', 'cover', 'breadCrumb', 'middlewares', 'roles', 'permissions');

        $this->cache->tags('sm')->rememberForever($this->getCrntLocale() . "-$routeName", function () use ($compact) {
            return $compact;
        });

        $route = $this->getRouteUrl($url, $prefix);

        $uses = $action
            ? starts_with($action, '\\') ? $action : "\\$action"
            : '\ctf0\SimpleMenu\Controllers\DummyController@handle';

        $mds = array_filter(Arr::flatten([$middlewares, $roles, $permissions]));

        app('router')->get($route)
            ->uses($uses)
            ->name($routeName)
            ->middleware($mds);
    }

    protected function createRoutesList($page)
    {
        $routeName = $page->route_name;

        foreach ($this->AppLocales() as $code) {
            $url = $page->getTranslationWithoutFallback('url', $code);
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
        $data = "<?php\n\nreturn " . var_export($routes, true) . ';';
        $data = $this->clearExtraSlash($data);

        // array(...) to [...]
        $data = str_replace('array (', '[', $data);
        $data = str_replace(')', ']', $data);
        $data = preg_replace('/=>\s+\[/', '=> [', $data);

        return app('files')->put($this->listFileDir, $data);
    }

    protected function clearExtraSlash($url)
    {
        return preg_replace('/\/+/', '/', $url);
    }
}