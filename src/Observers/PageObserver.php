<?php

namespace ctf0\SimpleMenu\Observers;

use ctf0\SimpleMenu\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PageObserver
{
    /**
     * Listen to the User created event.
     */
    public function created(Page $page)
    {
        File::delete(config('simpleMenu.routeListPath'));
    }

    /**
     * Listen to the User updated event.
     */
    public function updated(Page $page)
    {
        $this->cleanData($page);
    }

    /**
     * Listen to the User deleting event.
     */
    public function deleted(Page $page)
    {
        $this->cleanData($page);
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
        foreach (array_keys(LaravelLocalization::getSupportedLocales()) as $code) {
            // clear menu cache
            $page->menuNames->pluck('name')->each(function ($item) use ($code) {
                $name = "{$item}Menu-{$code}Pages";
                Cache::forget($name);
            });
            // clear page cache
            Cache::forget("{$code}-{$page->route_name}");
        }

        // clear page session
        session()->forget($page->route_name);

        // remove the route file
        File::delete(config('simpleMenu.routeListPath'));
    }
}
