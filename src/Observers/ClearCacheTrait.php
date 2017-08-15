<?php

namespace ctf0\SimpleMenu\Observers;

use Illuminate\Support\Facades\Cache;

trait ClearCacheTrait
{
    protected function clearCache($key_name)
    {
        $redis = Cache::getRedis();
        $keys  = $redis->keys("*$key_name*");
        foreach ($keys as $key) {
            $redis->del($key);
        }
    }

    protected function clearPagesCache()
    {
        Cache::forget('sm-pages');
    }

    protected function clearMenusCache()
    {
        Cache::forget('sm-menus');
    }
}
