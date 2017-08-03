<?php

namespace ctf0\SimpleMenu\Observers;

use ctf0\SimpleMenu\Models\Menu;
use Illuminate\Support\Facades\Cache;
use ctf0\SimpleMenu\Facade\SimpleMenu;

class MenuObserver
{
    /**
     * Listen to the Menu saved event.
     */
    public function saved(Menu $menu)
    {
        return $this->cleanData($menu);
    }

    /**
     * Listen to the Menu deleting event.
     */
    public function deleted(Menu $menu)
    {
        return $this->cleanData($menu);
    }

    /**
     * helpers.
     *
     * @param [type] $menu [description]
     *
     * @return [type] [description]
     */
    protected function cleanData($menu)
    {
        foreach (SimpleMenu::AppLocales() as $code) {
            Cache::forget("{$menu->name}Menu-{$code}Pages");
        }
    }
}
