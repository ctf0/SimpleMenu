<?php

namespace ctf0\SimpleMenu\Controllers;

use Illuminate\Support\Facades\Route;
use ctf0\SimpleMenu\Facade\SimpleMenu;

class DummyController extends BaseController
{
    public function handle()
    {
        extract(SimpleMenu::getRouteData(Route::currentRouteName()));

        return view("$this->templatePath.$template", compact('title', 'body', 'desc', 'meta', 'cover', 'breadCrumb'));
    }
}
