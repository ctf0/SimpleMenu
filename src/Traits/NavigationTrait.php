<?php

namespace ctf0\SimpleMenu\Traits;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait NavigationTrait
{
    /**
     * resolve route & params for lang switcher.
     *
     * @param [type] $name [description]
     * @param [type] $code [description]
     *
     * @return [type] [description]
     */
    public function getUrl($name, $code)
    {
        // routeName is not saved in the db (ex.php artisan make:auth)
        // or only url
        $routesListFile = include $this->listFileDir;

        if (is_null($name) || !array_get($routesListFile, $name)) {
            return LaravelLocalization::getLocalizedURL(
                $code, null, [], true
            );
        }

        $url = $this->routeLink($name, $code);

        // if we have a saved params
        if (session()->has($name)) {
            $params = session()->get($name);

            return LaravelLocalization::getLocalizedURL(
                $code, url($this->getParams($url, $params)), [], true
            );
        }

        // no params
        return LaravelLocalization::getLocalizedURL(
            $code, url($this->rmvUnUsedParams($url)), [], true
        );
    }

    /**
     * resolve route & params for links.
     *
     * @param [type]     $crntRouteName [description]
     * @param array|null $params        [description]
     *
     * @return [type] [description]
     */
    public function getRoute($crntRouteName, array $params = null)
    {
        $code = LaravelLocalization::getCurrentLocale();
        $url = $this->routeLink($crntRouteName, $code);

        // resolve route params
        if ($params) {
            foreach ($params as $key => $value) {
                if ($crntRouteName == $key) {
                    if (!session()->has($crntRouteName)) {
                        session([$crntRouteName => $value]);
                    }

                    // fix link not being 'is-active' when "hideDefaultLocaleInURL => true"
                    if (LaravelLocalization::hideDefaultLocaleInURL() && $code == LaravelLocalization::getDefaultLocale()) {
                        return url($this->getParams($url, $value));
                    }

                    return LaravelLocalization::getLocalizedURL(
                        $code, url($this->getParams($url, $value)), [], true
                    );
                }
            }
        }

        return route($crntRouteName);
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
        $routesListFile = include $this->listFileDir;

        // check if we have a link according to that "routeName & code"
        $searchCode = array_get($routesListFile, "$name.$code");

        // if notFound, then either redir to home or abort
        if (!$searchCode) {
            switch (config('simpleMenu.unFoundLocalizedRoute')) {
                case 'home':
                    return '/';
                    break;
                case 'error':
                    return '404';
                    break;
            }
        }

        return $searchCode;
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
    public function render($pages, $classes = null, $params = null, $url)
    {
        switch ($classes) {
            case 'config':
                $ul = config('simpleMenu.listClasses.ul');
                $li = config('simpleMenu.listClasses.li');
                $a = config('simpleMenu.listClasses.a');
                break;
            default:
                $ul = array_get($classes, 'ul');
                $li = array_get($classes, 'li');
                $a = array_get($classes, 'a');
                break;
        }

        return $this->getHtml($pages, $ul, $li, $a, $params, $url);
    }

    /**
     * generate menu html.
     *
     * @param [type] $pages  [description]
     * @param [type] $ul     [description]
     * @param [type] $li     [description]
     * @param [type] $a      [description]
     * @param [type] $params [description]
     * @param [type] $url    [description]
     *
     * @return [type] [description]
     */
    protected function getHtml($pages, $ul, $li, $a, $params, $url)
    {
        $html = '';
        $html .= "<ul class=\"{$ul}\">";

        foreach ($pages as $one) {
            // escape empty url
            if (empty($one->getTranslationWithoutFallback('url', app()->getLocale()))) {
                continue;
            }

            $routeUrl = $this->getRoute($one->route_name, $params);
            $isActive = ($url == $routeUrl ? 'is-active' : '');

            $html .= "<li class=\"{$li}\">";
            $html .= "<a href=\"{$routeUrl}\" class=\"{$isActive}\">{$one->title}</a>";

            if (count($childs = $one->getImmediateDescendants())) {
                $html .= $this->getHtml($childs, $ul, $li, $a, $params, $url);
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
            $url = preg_replace('/\{'.preg_quote($key).'(\?)?\}/', $value, $url);
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
