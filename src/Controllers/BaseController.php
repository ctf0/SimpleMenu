<?php

namespace ctf0\SimpleMenu\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $cache;
    protected $adminPath;
    protected $crud_prefix;
    protected $userModel;

    public function __construct()
    {
        if (is_callable('parent::__construct')) {
            parent::__construct();
        }

        $this->cache        = app('cache');
        $this->adminPath    = 'SimpleMenu::admin.' . config('simpleMenu.framework');
        $this->crud_prefix  = config('simpleMenu.crud_prefix');
        $this->userModel    = app(config('simpleMenu.userModel'));
    }
}
