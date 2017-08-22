<?php

namespace ctf0\SimpleMenu\Observers;

class UserObserver extends BaseObserver
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
     * @return [type] [description]
     */
    protected function cleanData()
    {
        $this->cache->forget('sm-users');

        event('sm-users.cleared');
    }
}
