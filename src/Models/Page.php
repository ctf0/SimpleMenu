<?php

namespace App;

use Baum\Node;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class Page extends Node
{
    use HasRoles, HasTranslations;

    public $translatable = ['title', 'body', 'prefix', 'url'];
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            Cache::forget('pages');
            File::delete(config('simpleMenu.routeListPath'));
        });
        static::updated(function ($model) {
            Cache::forget('pages');
            File::delete(config('simpleMenu.routeListPath'));
        });
        static::deleted(function ($model) {
            Cache::forget('pages');
            File::delete(config('simpleMenu.routeListPath'));
        });
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
