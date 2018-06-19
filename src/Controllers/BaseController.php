<?php

namespace ctf0\SimpleMenu\Controllers;

use App\Http\Controllers\Controller;
use ctf0\SimpleMenu\Controllers\Admin\Traits\Paginate;

class BaseController extends Controller
{
    use Paginate;

    protected $cache;
    protected $adminPath;
    protected $crud_prefix;

    protected $userModel;
    protected $pageModel;
    protected $menuModel;
    protected $roleModel;
    protected $permissionModel;

    public function __construct()
    {
        if (is_callable('parent::__construct')) {
            parent::__construct();
        }

        $config                = config('simpleMenu');
        $this->cache           = app('cache');
        $this->adminPath       = 'SimpleMenu::admin';
        $this->crud_prefix     = array_get($config, 'crud_prefix');
        $this->userModel       = app(array_get($config, 'models.user'));
        $this->pageModel       = app(array_get($config, 'models.page'));
        $this->menuModel       = app(array_get($config, 'models.menu'));

        $this->roleModel       = app(config('permission.models.role'));
        $this->permissionModel = app(config('permission.models.permission'));
    }
}
