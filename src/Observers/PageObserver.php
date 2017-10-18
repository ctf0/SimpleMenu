<?php

namespace ctf0\SimpleMenu\Observers;

use ctf0\SimpleMenu\Models\Page;

class PageObserver extends BaseObserver
{
    /**
     * Listen to the Page saved event.
     */
    public function saved(Page $page)
    {
        return $this->cleanData($page);
    }

    /**
     * Listen to the Page deleted event.
     */
    public function deleted(Page $page)
    {
        return $this->cleanData($page);
    }

    /**
     * helpers.
     *
     * @param [type] $page [description]
     *
     * @return [type] [description]
     */
    protected function cleanData($page)
    {
        $route_name = $page->route_name;

        // clear page session
        session()->forget($route_name);

        // remove the route file
        app('files')->delete(config('simpleMenu.routeListPath'));

        // clear all cache
        $this->cache->tags('sm')->flush();

        event('sm-pages.cleared');
    }
}
