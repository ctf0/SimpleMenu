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

        static::updated(function ($model) {
            foreach (array_keys(LaravelLocalization::getSupportedLocales()) as $code) {
                $name = "{$model->name}Menu-{$code}Pages";
                Cache::forget($name);
            }
        });

        static::deleted(function ($model) {
            foreach (array_keys(LaravelLocalization::getSupportedLocales()) as $code) {
                $name = "{$model->name}Menu-{$code}Pages";
                Cache::forget($name);
            }
        });
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class);
    }
}
