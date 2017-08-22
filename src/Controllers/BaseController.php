<?php

namespace ctf0\SimpleMenu\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $templatePath;
    protected $adminPath;
    protected $userModel;
    protected $crud_prefix;
    protected $cache;

    public function __construct()
    {
        if (is_callable('parent::__construct')) {
            parent::__construct();
        }

        $this->cache        = app('cache');
        $fw                 = config('simpleMenu.framework');
        $this->adminPath    = "SimpleMenu::admin.{$fw}";
        $this->templatePath = config('simpleMenu.templatePath');
        $this->crud_prefix  = config('simpleMenu.crud_prefix');

        $this->userModel    = app(config('simpleMenu.userModel'));
    }
}
