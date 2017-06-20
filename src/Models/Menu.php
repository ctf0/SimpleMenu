<?php

namespace ctf0\SimpleMenu\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasTranslations;

    protected $guarded = ['id'];
    protected $with = ['pages'];

    public function pages()
    {
        return $this->belongsToMany(Page::class);
    }
}
