<?php

namespace ctf0\SimpleMenu\Controllers;

use ctf0\SimpleMenu\Facade\SimpleMenu;

class DummyController extends BaseController
{
    public function handle()
    {
        extract(SimpleMenu::getRouteData(app('router')->currentRouteName()));

        return view($template, compact('title', 'body', 'desc', 'meta', 'cover', 'breadCrumb'));
    }
}
