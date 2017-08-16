<?php

namespace ctf0\SimpleMenu\Observers;

use ctf0\SimpleMenu\Models\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class PageObserver
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
        // due to issues with baum\node
        Cache::flush();
    }
}
