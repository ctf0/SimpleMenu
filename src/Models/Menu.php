<?php

namespace ctf0\SimpleMenu\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use ClearCacheTrait;

    protected $guarded = ['id'];

    public function pages()
    {
        return $this->belongsToMany(Page::class)->withPivot('order');
    }

    public function cleanData()
    {
        return $this->clearCache('Menu');
    }
}
