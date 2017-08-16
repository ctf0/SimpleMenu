<?php

namespace ctf0\SimpleMenu\Observers;

use ctf0\SimpleMenu\Models\Page;
use Illuminate\Support\Facades\File;

class PageObserver extends BaseObserver
{
    /**
     * Listen to the User saved event.
     */
    public function saved(Page $page)
    {
        return $this->cleanData($page);
    }

    /**
     * Listen to the User deleted event.
     */
    public function deleted(Page $page)
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

        // clear all cache
        $this->cache->tags('sm')->flush();
    }
}
