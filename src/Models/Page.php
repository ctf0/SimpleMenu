<?php

namespace ctf0\SimpleMenu\Models;

use Baum\Node;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class Page extends Node
{
    use HasRoles, HasTranslations;

    protected $guarded = ['id'];
    public $translatable = ['title', 'body', 'prefix', 'url'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            File::delete(config('simpleMenu.routeListPath'));
        });

        static::updated(function ($model) {
            File::delete(config('simpleMenu.routeListPath'));
        });

        static::deleted(function ($model) {
            File::delete(config('simpleMenu.routeListPath'));
        });
    }

    public function menuNames()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function roles()
    {
        return $this->belongsToMany(
            config('laravel-permission.models.role'), 'page_has_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(
            config('laravel-permission.models.permission'), 'page_has_permissions');
    }
}
