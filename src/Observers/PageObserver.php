<?php

namespace ctf0\SimpleMenu\Observers;

use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

        foreach (array_keys(LaravelLocalization::getSupportedLocales()) as $code) {
            // clear menu cache
            Menu::get()->pluck('name')->each(function ($item) use ($code) {
                Cache::forget("{$item}Menu-{$code}Pages");
            });

            // clear page cache
            Cache::forget("$code-$route_name");
        }
    }
}
