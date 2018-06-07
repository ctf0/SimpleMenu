<?php

namespace ctf0\SimpleMenu\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $cache;
    protected $adminPath;
    protected $crud_prefix;
    protected $userModel;
    protected $pageModel;
    protected $menuModel;

    public function __construct()
    {
        if (is_callable('parent::__construct')) {
            parent::__construct();
        }

        $this->cache           = app('cache');
        $this->adminPath       = 'SimpleMenu::admin';
        $this->crud_prefix     = config('simpleMenu.crud_prefix');

        $this->userModel       = app(config('simpleMenu.models.user'));
        $this->pageModel       = app(config('simpleMenu.models.page'));
        $this->menuModel       = app(config('simpleMenu.models.menu'));
        $this->roleModel       = app(config('permission.models.role'));
        $this->permissionModel = app(config('permission.models.permission'));
    }
}
