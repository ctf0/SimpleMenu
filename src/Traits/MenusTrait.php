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

        if (cache('sm-menus')->isNotEmpty()) {
            return Cache::rememberForever("{$name}Menu-{$locale}Pages", function () use ($name) {
                return collect(cache('sm-menus')->where('name', $name)->first()->pages)
                    ->sortBy('pivot_order')
                    ->filter(function ($item) {
                        return $item->url != '';
                    });
            });
        }
    }
}
