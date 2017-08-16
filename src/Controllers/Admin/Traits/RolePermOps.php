<?php

namespace ctf0\SimpleMenu\Controllers\Admin\Traits;

trait RolePermOps
{
    protected function clearCache()
    {
        $this->cache->tags('sm')->flush();
    }
}
