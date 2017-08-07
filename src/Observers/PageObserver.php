<?php

namespace ctf0\SimpleMenu\Observers;

use ctf0\SimpleMenu\Facade\SimpleMenu;
use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class PageObserver
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
        $page->roles()->detach();
        $page->permissions()->detach();

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
        File::delete(config('simpleMenu.routeListPath'));

        foreach (SimpleMenu::AppLocales() as $code) {
            // clear menu cache
            cache('sm-menus')->pluck('name')->each(function ($name) use ($code) {
                Cache::forget("{$name}Menu-{$code}Pages");
            });

            // clear page cache
            Cache::forget("$code-$route_name");
        }
    }
}
