<?php

namespace ctf0\SimpleMenu\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $templatePath;
    protected $adminPath;
    protected $userModel;
    protected $crud_prefix;

    public function __construct()
    {
        $fw                 = config('simpleMenu.framework');
        $this->adminPath    = "SimpleMenu::admin.{$fw}";
        $this->templatePath = config('simpleMenu.templatePath');
        $this->crud_prefix  = config('simpleMenu.crud_prefix');

        $this->userModel    = app(config('simpleMenu.userModel'));
    }
}
