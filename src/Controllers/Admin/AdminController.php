<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * home.
     *
     * @return [type] [description]
     */
    public function index()
    {
        return view('SimpleMenu::admin.'.config('simpleMenu.framework').'.admin')->with([
            'title' => 'DashBoard',
            'desc'  => 'Admin Description',
        ]);
    }
}
