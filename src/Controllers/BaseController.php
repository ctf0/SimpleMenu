<?php

namespace ctf0\SimpleMenu\Controllers;

use Illuminate\Support\Arr;
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

        $sm_config = config('simpleMenu');
        $sp_config = config('permission');

        $this->cache       = app('cache');
        $this->adminPath   = 'SimpleMenu::admin';
        $this->crud_prefix = $sm_config['crud_prefix'];

        $this->userModel       = app(Arr::get($sm_config, 'models.user'));
        $this->pageModel       = app(Arr::get($sm_config, 'models.page'));
        $this->menuModel       = app(Arr::get($sm_config, 'models.menu'));
        $this->roleModel       = app(Arr::get($sp_config, 'models.role'));
        $this->permissionModel = app(Arr::get($sp_config, 'models.permission'));
    }
}
