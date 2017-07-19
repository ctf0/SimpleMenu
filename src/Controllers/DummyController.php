<?php

namespace ctf0\SimpleMenu\Controllers;

use App\Http\Controllers\Controller;
use ctf0\SimpleMenu\Facade\SimpleMenu;
use Illuminate\Support\Facades\Route;

class DummyController extends Controller
{
    public function handle()
    {
        extract(SimpleMenu::getRouteData(Route::currentRouteName()));

        return view(config('simpleMenu.templatePath').".$template", compact('title', 'body', 'desc', 'breadCrumb'));
    }
}
