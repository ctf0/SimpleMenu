<?php

namespace ctf0\SimpleMenu\Observers;

class MenuObserver extends BaseObserver
{
    /**
     * Listen to the Menu saved event.
     */
    public function saved()
    {
        return $this->cleanData();
    }

    /**
     * Listen to the Menu deleted event.
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
        $this->cache->tags('sm')->flush();

        event('sm-menus.cleared');
    }
}
