<?php

namespace App\Http\Models;

use Spatie\Permission\Traits\HasRoles;

class Page extends BaseModel
{
    use HasRoles;

    public $translatable = ['prefix', 'title', 'body'];

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

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            Cache::forget('pages');
        });
        static::updated(function ($model) {
            Cache::forget('pages');
        });
        static::deleted(function ($model) {
            Cache::forget('pages');
        });
    }
}
