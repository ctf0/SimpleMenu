<?php

namespace ctf0\SimpleMenu\Observers;

use Illuminate\Support\Facades\Cache;

class UserObserver
{
    /**
     * Listen to the User saved event.
     */
    public function saved()
    {
        return $this->cleanData();
    }

    /**
     * Listen to the User deleting event.
     */
    public function deleted()
    {
        return $this->cleanData();
    }

    /**
     * helpers.
     *
     * @param [type] $menu [description]
     *
     * @return [type] [description]
     */
    protected function cleanData()
    {
        Cache::forget('sm-users');
    }
}
