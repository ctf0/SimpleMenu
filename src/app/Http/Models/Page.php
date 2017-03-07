<?php

namespace App\Http\Models;

use Baum\Node;
use Cache;
use File;
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
            File::delete(config_path('simpleMenuTemp.php'));
        });
        static::updated(function ($model) {
            Cache::forget('pages');
            File::delete(config_path('simpleMenuTemp.php'));
        });
        static::deleted(function ($model) {
            Cache::forget('pages');
            File::delete(config_path('simpleMenuTemp.php'));
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
