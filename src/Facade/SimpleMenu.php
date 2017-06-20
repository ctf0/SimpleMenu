<?php

namespace ctf0\SimpleMenu\Facade;

use Illuminate\Support\Facades\Facade;

class SimpleMenu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'simplemenu';
    }
}
