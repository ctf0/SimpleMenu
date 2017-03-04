<?php

namespace App;

class SimpleMenu
{
    use createRoutes, createMenus;

    public $defLocale;

    public function __construct()
    {
        view()->share('menu', $this);
        $this->defLocale = config('app.fallback_locale');
    }
}
