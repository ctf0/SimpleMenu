<?php

namespace ctf0\SimpleMenu\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class DummyController extends Controller
{
    public function handle()
    {
        extract(cache(LaravelLocalization::getCurrentLocale().'-'.Route::currentRouteName()));

        return view(config('simpleMenu.templatePath').".$template", compact('title', 'body', 'desc', 'breadCrump'));
    }
}
