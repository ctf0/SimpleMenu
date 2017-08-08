<?php

namespace ctf0\SimpleMenu\Traits;

use ctf0\SimpleMenu\Models\Menu;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait MenusTrait
{
    /**
     * register routes for menu pages.
     *
     * @return [type] [description]
     */
    public function createMenus()
    {
        Cache::rememberForever('sm-menus', function () {
            return Menu::with('pages')->get();
        });

        cache('sm-menus')->pluck('name')->each(function ($name) {
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
        $locale = LaravelLocalization::getCurrentLocale();

        return Cache::rememberForever("{$name}Menu-{$locale}Pages", function () use ($name) {
            return collect(
                    cache('sm-menus')->where('name', $name)->first()->pages
                )->sortBy('pivot_order');
        });
    }
}
