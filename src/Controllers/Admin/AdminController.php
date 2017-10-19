<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use ctf0\SimpleMenu\Controllers\BaseController;

class AdminController extends BaseController
{
    /**
     * home.
     *
     * @return [type] [description]
     */
    public function index()
    {
        return view("{$this->adminPath}.admin")->with([
            'title' => 'DashBoard',
            'desc'  => 'Admin DashBoard',
        ]);
    }
}
