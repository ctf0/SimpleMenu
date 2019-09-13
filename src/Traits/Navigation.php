<?php

namespace ctf0\SimpleMenu\Traits;

use Illuminate\Support\Arr;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait Navigation
{
    protected $urlRoute;

    /**
     * resolve route & params for lang switcher.
     *
     * @param [type] $name [description]
     * @param [type] $code [description]
     *
     * @return [type] [description]
     */
    public function getUrl($code)
    {
        $name = app('router')->currentRouteName();

        // redir to '/' if the first item in "bc" is in diff locale
        $bc = $this->getRouteData($name)['breadCrumb'];

        if (isset($bc) && count($bc) && !$this->searchForRoute($bc->pluck('route_name')->first(), $code)) {
            return LaravelLocalization::getLocalizedURL($code, url('/'), [], true);
        }

        // routeName is not saved in the db (ex.php artisan make:auth)
        // or only url
        $routesListFile = include $this->listFileDir;

        if (is_null($name) || !isset($routesListFile[$name])) {
            return LaravelLocalization::getLocalizedURL($code, null, [], true);
        }

        $url = $this->routeLink($name, $code);

        // if we have a saved params
        if (session()->has($name)) {
            $params = session()->get($name);

            return LaravelLocalization::getLocalizedURL($code, url($this->getParams($url, $params)), [], true);
        }

        // no params
        return LaravelLocalization::getLocalizedURL($code, url($this->rmvUnUsedParams($url)), [], true);
    }

    /**
     * resolve route & params for links.
     *
     * @param [type]     $crntRouteName [description]
     * @param array|null $params        [description]
     *
     * @return [type] [description]
     */
    public function getRoute($crntRouteName, $params = null)
    {
        // where route is available under one locale but not the other
        if (!app('router')->has($crntRouteName)) {
            return;
        }

        $locale               = $this->getCrntLocale();
        $url                  = $this->routeLink($crntRouteName, $locale);
        $forceDefaultLocation = LaravelLocalization::hideDefaultLocaleInURL() && $locale == LaravelLocalization::getDefaultLocale() ? false : true;

        // resolve route params
        if ($params) {
            foreach ($params as $key => $value) {
                if ($crntRouteName == $key) {
                    session([$key => $value]);

                    // fix link not being 'is-active'when"hideDefaultLocaleInURL => true"
                    $finalUrl       = LaravelLocalization::getLocalizedURL($locale, url($this->getParams($url, $value)), [], $forceDefaultLocation);
                    $this->urlRoute = $finalUrl;

                    return $finalUrl;
                }
            }
        }

        $this->urlRoute = route($crntRouteName);

        return $this->urlRoute;
    }

    /**
     * helpers.
     *
     * @return [type] [description]
     */
    public function routeUrl()
    {
        return $this->urlRoute;
    }

    public function isActiveRoute()
    {
        return request()->url() == $this->urlRoute;
    }

    public function getRouteData($name)
    {
        return $this->cache->tags('sm')->get($this->getCrntLocale() . "-$name");
    }

    public function getBC()
    {
        $name = app('router')->currentRouteName();
        $bc   = $this->getRouteData($name)['breadCrumb'];

        if (isset($bc) && count($bc)) {
            return $bc;
        }
    }

    /**
     * resolve route url.
     *
     * @param [type] $name [description]
     * @param [type] $code [description]
     *
     * @return [type] [description]
     */
    protected function routeLink($name, $code)
    {
        $searchCode = $this->searchForRoute($name, $code);

        // if notFound, then either redir to home or show 404
        if (!$searchCode) {
            switch (config('simpleMenu.unFoundLocalizedRoute')) {
                case 'error':
                    return '404';
                    break;
                default:
                    return '/';
            }
        }

        return $searchCode;
    }

    protected function searchForRoute($name, $code)
    {
        $routesListFile = include $this->listFileDir;

        // check if we have a link according to that "routeName & code"
        return Arr::get($routesListFile, "$name.$code", false);
    }

    /**
     * render menu.
     *
     * @param [type] $pages   [description]
     * @param [type] $classes [description]
     * @param [type] $params  [description]
     * @param [type] $url     [description]
     *
     * @return [type] [description]
     */
    public function render($pages, $classes = null, $params = null)
    {
        $url = request()->url();

        switch ($classes) {
            case 'config':
                $ul = config('simpleMenu.listClasses.ul');
                $li = config('simpleMenu.listClasses.li');
                $a  = config('simpleMenu.listClasses.a');
                break;
            default:
                $ul = $classes['ul'];
                $li = $classes['li'];
                $a  = $classes['a'];
                break;
        }

        return $this->getHtml($pages, $ul, $li, $a, $params, $url);
    }

    /**
     * generate menu html.
     *
     * @param [type] $pages        [description]
     * @param [type] $params       [description]
     * @param [type] $url          [description]
     * @param mixed  $ul_ClassName
     * @param mixed  $li_ClassName
     * @param mixed  $a_ClassName
     *
     * @return [type] [description]
     */
    protected function getHtml($pages, $ul_ClassName, $li_ClassName, $a_ClassName, $params, $url)
    {
        $html = '';
        $html .= "<ul class = \"{$ul_ClassName}\">";

        foreach ($pages as $page) {
            // escape empty url
            if (empty($page->url)) {
                continue;
            }

            $routeUrl = $this->getRoute($page->route_name, $params);
            $isActive = ($url == $routeUrl ? $a_ClassName : '');

            $html .= "<li class = \"{$li_ClassName}\">";
            $html .= "<a href = \"{$routeUrl}\" class = \"{$isActive}\">{$page->title}</a>";

            if ($childs = $page->nests) {
                $html .= $this->getHtml($childs, $ul_ClassName, $li_ClassName, $a_ClassName, $params, $url);
            }
            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * resolve params.
     *
     * @param [type] $url    [description]
     * @param [type] $params [description]
     *
     * @return [type] [description]
     */
    protected function getParams($url, $params)
    {
        foreach ($params as $key => $value) {
            $url = preg_replace('/\{' . preg_quote($key) . '(\?)?\}/', $value, $url);
        }

        return $this->rmvUnUsedParams($url);
    }

    /**
     * remove optional params. so we dont get badly formated url.
     *
     * @param [type] $url [description]
     *
     * @return [type] [description]
     */
    protected function rmvUnUsedParams($url)
    {
        return preg_replace('/\{.*\}/', '', $url);
    }
}
