<?php

namespace App;

use LaravelLocalization;

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
            // page is hardcoded
            return LaravelLocalization::getLocalizedURL($code);
        }

        // if we have a saved params
        if (session()->has($name)) {
            $params = session()->get($name);

            return LaravelLocalization::getLocalizedURL($code, url($this->getParams($url, $params)));
        }

        // no params
        return LaravelLocalization::getLocalizedURL($code, url($this->rmvOptParams($url)));
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

        if (($crntRouteName == $another) && $params) {
            // set a session item so we can redir with params
            // from the lang switcher
            if (!session()->has($crntRouteName)) {
                session([$crntRouteName => $params]);
            }

            return LaravelLocalization::getLocalizedURL($code, url($this->getParams($url, $params)));
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
        $file = include $this->listFileDir;

        return array_get($file, "$name.$code");
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

        return $this->rmvOptParams($url);
    }

    /**
     * remove optional params.
     * so we dont get badly formated url.
     *
     * @param [type] $url [description]
     *
     * @return [type] [description]
     */
    protected function rmvOptParams($url)
    {
        return preg_replace('/\{.*\}/', '', $url);
    }
}
