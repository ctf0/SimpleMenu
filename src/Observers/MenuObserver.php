<?php

namespace ctf0\SimpleMenu\Observers;

use ctf0\SimpleMenu\Models\Menu;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class MenuObserver
{
    /**
     * Listen to the User created event.
     */
    public function created(Menu $menu)
    {
        $this->cleanData($menu);
    }

    /**
     * Listen to the User deleting event.
     */
    public function deleted(Menu $menu)
    {
        $this->cleanData($menu);
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
        foreach (array_keys(LaravelLocalization::getSupportedLocales()) as $code) {
            $name = "{$menu->name}Menu-{$code}Pages";
            Cache::forget($name);
        }
    }
}
