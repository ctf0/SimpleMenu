<?php

namespace ctf0\SimpleMenu\Observers;

use ctf0\SimpleMenu\Models\Menu;

class MenuObserver
{
    use ClearCacheTrait;

    /**
     * Listen to the User saved event.
     */
    public function saved(Menu $menu)
    {
        return $this->cleanData($menu);
    }

    /**
     * Listen to the User deleted event.
     */
    public function deleted(Menu $menu)
    {
        return $this->cleanData($menu);
    }

    /**
     * helpers.
     *
     * @param [type] $menu [description]
     * @param mixed  $page
     *
     * @return [type] [description]
     */
    protected function cleanData($menu)
    {
        $this->clearCache("{$menu->name}Menu");
        $this->clearMenusCache();
    }
}
