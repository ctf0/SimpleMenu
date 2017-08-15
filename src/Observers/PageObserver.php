<?php

namespace ctf0\SimpleMenu\Observers;

use ctf0\SimpleMenu\Models\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    use ClearCacheTrait;

    /**
     * Listen to the User saving event.
     */
    public function saving(Page $page)
    {
        return $this->cleanData($page);
    }

    /**
     * Listen to the User deleting event.
     */
    public function deleting(Page $page)
    {
        return $this->cleanData($page);
    }

    /**
     * helpers.
     *
     * @param [type] $menu [description]
     * @param mixed  $page
     *
     * @return [type] [description]
     */
    protected function cleanData($page)
    {
        $route_name = $page->route_name;

        // clear page session
        session()->forget($route_name);

        // remove the route file
        File::delete(config('simpleMenu.routeListPath'));

        // clear page cache
        $this->clearCache($route_name);
        $this->clearCache('_ancestors');
        $this->clearCache('_nests');

        // clear menu cache
        foreach ($page->menus->pluck('name') as $menu) {
            $this->clearCache("{$menu}Menu");
        }

        $this->clearPagesCache();
    }
}
