<?php

namespace ctf0\SimpleMenu\Models;

use Baum\Node;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class Page extends Node
{
    use HasRoles, HasTranslations;

    protected $guarded    = ['id'];
    protected $guard_name = 'web';
    public $translatable  = ['title', 'body', 'desc', 'prefix', 'url'];

    public function menuNames()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function syncMenus($menus)
    {
        return $this->menuNames()->sync($menus);
    }

    public function assignToMenus($menus)
    {
        return $this->menuNames()->attach($menus);
    }
}
