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
        $url = $this->routeLink($name, $code);

        if (!$url) {
            // page is hardcoded, means its not saved in the db (ex.php artisan make:auth routes)
            return LaravelLocalization::getLocalizedURL($code, null, [], true);
        }

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
     * @param [type] $crntRouteName [description]
     * @param [type] $another       [description]
     * @param [type] $params        [description]
     *
     * @return [type] [description]
     */
    public function getRoute($crntRouteName, $another = null, $params = null)
    {
        $code = LaravelLocalization::getCurrentLocale();
        $url = $this->routeLink($crntRouteName, $code);

        if (is_array($another)) {
            foreach ($another as $k => $v) {
                if ($crntRouteName == $k) {
                    if (!session()->has($crntRouteName)) {
                        session([$crntRouteName => $v]);
                    }

                    return $this->checkForhideDefaultLocaleInURL($code, $url, $v);
                }
            }
        } else {
            if (($crntRouteName == $another) && $params) {
                // set a session item so we can redir with params
                // from the lang switcher
                if (!session()->has($crntRouteName)) {
                    session([$crntRouteName => $params]);
                }

                return $this->checkForhideDefaultLocaleInURL($code, $url, $params);
            }
        }

        return route($crntRouteName);
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
    public function render($pages, $classes = null, $params = null, $url = null)
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
     * to resolve 'hideDefaultLocaleInURL => true' problem.
     *
     * @param [type] $code   [description]
     * @param [type] $url    [description]
     * @param [type] $params [description]
     *
     * @return [type] [description]
     */
    protected function checkForhideDefaultLocaleInURL($code, $url, $params)
    {
        if (LaravelLocalization::hideDefaultLocaleInURL() &&
            LaravelLocalization::getCurrentLocale() == app('laravellocalization')->getDefaultLocale()) {
            return url($this->getParams($url, $params));
        }

        return LaravelLocalization::getLocalizedURL(
            $code, url($this->getParams($url, $params)), [], true
        );
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
        $file = include $this->listFileDir;

        // in case 'hideDefaultLocaleInURL => true'
        if (LaravelLocalization::hideDefaultLocaleInURL() && !$code) {
            $code = app('laravellocalization')->getDefaultLocale();
        }

        // check if we have a link according to that "routeName & code"
        $search = array_get($file, "$name.$code");

        // if notFound, then either redir to home or abort
        if (!$search) {
            switch (config('simpleMenu.unFoundLocalizedRoute')) {
                case 'home':
                    return '/';
                    break;
                case 'error':
                    return '404';
                    break;
            }
        }

        return $search;
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
     * remove optional params.
     * so we dont get badly formated url.
     *
     * @param [type] $url [description]
     *
     * @return [type] [description]
     */
    protected function rmvUnUsedParams($url)
    {
        return preg_replace('/\{.*\}/', '', $url);
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
            $routeUrl = $this->getRoute($one->route_name, $params);
            $isActive = $url == $routeUrl ? 'is-active' : '';

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
}
