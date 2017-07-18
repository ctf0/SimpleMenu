<?php

namespace ctf0\SimpleMenu\Models;

use Baum\Node;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class Page extends Node
{
    use HasRoles, HasTranslations;

    protected $guarded   = ['id'];
    public $translatable = ['title', 'body', 'desc', 'prefix', 'url'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            File::delete(config('simpleMenu.routeListPath'));
        });

        static::updated(function ($model) {
            foreach (array_keys(LaravelLocalization::getSupportedLocales()) as $code) {
                $model->menuNames->pluck('name')->each(function ($item) {
                    $name = "{$item}Menu-{$code}Pages";
                    Cache::forget($name);
                });
                Cache::forget("{$code}-{$model->route_name}");
            }

            File::delete(config('simpleMenu.routeListPath'));
        });

        static::deleted(function ($model) {
            foreach (array_keys(LaravelLocalization::getSupportedLocales()) as $code) {
                $model->menuNames->pluck('name')->each(function ($item) {
                    $name = "{$item}Menu-{$code}Pages";
                    Cache::forget($name);
                });
                Cache::forget("{$code}-{$model->route_name}");
            }

            File::delete(config('simpleMenu.routeListPath'));
        });
    }

    public function menuNames()
    {
        return $this->belongsToMany(Menu::class);
    }
}
