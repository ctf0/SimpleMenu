<?php

namespace ctf0\SimpleMenu\Models;

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
            Cache::forget('SimpleMenu-menus');
        });
        static::updated(function ($model) {
            Cache::forget('SimpleMenu-menus');
        });
        static::deleted(function ($model) {
            Cache::forget('SimpleMenu-menus');
        });
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class);
    }
}
