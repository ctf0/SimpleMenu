<?php

namespace ctf0\SimpleMenu\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $templatePath;
    protected $adminPath;
    protected $userModel;

    public function __construct()
    {
        $fw                 = config('simpleMenu.framework');
        $this->adminPath    = "SimpleMenu::admin.{$fw}";
        $this->userModel    = app(config('simpleMenu.userModel'));
        $this->templatePath = config('simpleMenu.templatePath');
    }
}
