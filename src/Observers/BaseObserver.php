<?php

namespace ctf0\SimpleMenu\Observers;

class BaseObserver
{
    protected $cache;

    public function __construct()
    {
        $this->cache = app()['cache'];
    }
}
