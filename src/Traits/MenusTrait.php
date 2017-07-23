<?php

namespace ctf0\SimpleMenu\Traits;

use ctf0\SimpleMenu\Models\Menu;
use Illuminate\Support\Facades\Cache;

trait MenusTrait
{
    /**
     * register routes for menu pages.
     *
     * @return [type] [description]
     */
    public function createMenus()
    {
        Menu::get()->pluck('name')->each(function ($name) {
            $this->viewComp($name);
        });
    }

    /**
     * [viewComp description].
     *
     * @param [type] $name [description]
     *
     * @return [type] [description]
     */
    public function viewComp($name)
    {
        $viewFile = view()->exists("SimpleMenu::menu.{$name}")
        ? "SimpleMenu::menu.{$name}"
        : 'SimpleMenu::menu.example';

        return view()->composer($viewFile, function ($view) use ($name) {
            $view->with([
                'PAGES'    => $this->query($name),
                'menuName' => $name,
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
        Cache::rememberForever("{$name}Menu-".app()->getLocale().'Pages', function () use ($name) {
            return app(Menu::class)->where('name', $name)->first()->pages()->orderBy('pivot_order', 'asc')->where('url->'.app()->getLocale(), '!=', '')->get();
        });

        return cache("{$name}Menu-".app()->getLocale().'Pages');
    }
}
