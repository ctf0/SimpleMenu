<?php

namespace ctf0\SimpleMenu\Traits;

trait MenusTrait
{
    /**
     * register routes for menu pages.
     *
     * @return [type] [description]
     */
    public function createMenus()
    {
        $this->cache->tags('sm')->get('menus')->pluck('name')->each(function ($name) {
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
        $locale = $this->getCrntLocale();

        if ($this->cache->tags('sm')->get('menus')->isNotEmpty()) {
            return $this->cache->tags('sm')->rememberForever("{$name}Menu-{$locale}Pages", function () use ($name) {
                return collect($this->cache->tags('sm')->get('menus')->where('name', $name)->first()->pages)
                    ->sortBy('pivot_order')
                    ->filter(function ($item) {
                        return $item->url != '';
                    });
            });
        }
    }
}
