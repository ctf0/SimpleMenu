<?php

namespace ctf0\SimpleMenu\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class DummyController extends Controller
{
    public function handle()
    {
        extract(cache(Route::currentRouteName()));

        return view(config('simpleMenu.templatePath').".$template")->with([
            'title'      => $title,
            'body'       => $body,
            'desc'       => $desc,
            'breadCrump' => $breadCrump,
        ]);
    }
}
