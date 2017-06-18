<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasTranslations;

    protected $guarded = ['id'];
    protected $with = ['pages'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            Cache::forget('menus');
        });
        static::updated(function ($model) {
            Cache::forget('menus');
        });
        static::deleted(function ($model) {
            Cache::forget('menus');
        });
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class);
    }
}
