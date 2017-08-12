<?php

namespace ctf0\SimpleMenu\Models;

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

        Cache::forget('sm-pages');
        Cache::forget('sm-menus');
    }
}
