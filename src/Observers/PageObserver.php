<?php

namespace ctf0\SimpleMenu\Observers;

use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use ctf0\SimpleMenu\Facade\SimpleMenu;

class PageObserver
{
    /**
     * Listen to the Page saved event.
     */
    public function saving(Page $page)
    {
        return $this->cleanData($page);
    }

    /**
     * Listen to the Page deleted event.
     */
    public function deleting(Page $page)
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
        $locales    = SimpleMenu::AppLocales();
        $menus      = Menu::pluck('name');

        // clear page session
        session()->forget($route_name);

        // remove the route file
        File::delete(config('simpleMenu.routeListPath'));

        foreach ($locales as $code) {
            foreach ($menus as $name) {
                // clear menu cache
                Cache::forget("{$name}Menu-{$code}Pages");
            }

            // clear page cache
            Cache::forget("$code-$route_name");
        }
    }
}
