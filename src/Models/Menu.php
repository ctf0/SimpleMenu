<?php

namespace ctf0\SimpleMenu\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = ['id'];

    public function pages()
    {
        return $this->belongsToMany(config('simpleMenu.models.page'))->withPivot('order');
    }
}
