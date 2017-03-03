<?php

namespace App\Http\Controllers;

class MenuController extends Controller
{
    protected $cache;

    public function __construct()
    {
        $this->cache = cache('menus');

        view()->share('menu', $this);

        foreach ($this->cache->pluck('name') as $name) {
            $this->genNavigation($name);
        }
    }

    /**
     * generate menu lists and assign its data.
     *
     * @param [type] $name [description]
     *
     * @return [type] [description]
     */
    public function genNavigation($name)
    {
        $viewFile = view()->exists("_partials.navigation.pages.{$name}")
        ? "_partials.navigation.pages.{$name}"
        : '_partials.navigation.pages.side';

        return view()->composer($viewFile, function ($view) use ($name) {
            $view->with([
                'PAGES'   => $this->query($name),
                'menuName'=> $name,
            ]);
        });
    }

    /**
     * menu logic.
     *
     * @param [type]     $name [description]
     * @param null|mixed $nest
     *
     * @return [type] [description]
     */
    public function query($name)
    {
        return $this->cache->where('name', $name)
                ->first()->pages()
                ->whereNotNull('menu_page.order')
                ->orderBy('menu_page.order', 'asc')
                ->get();
    }

    /**
     * menu item childs.
     *
     * @param [type] $name [description]
     * @param [type] $id   [description]
     *
     * @return [type] [description]
     */
    public function getChilds($name, $id)
    {
        return $this->cache->where('name', $name)
                ->first()->pages()
                ->where('menu_page.parent_id', $id)
                ->orderBy('menu_page.child_order', 'asc')
                ->get();
    }
}
