<?php

namespace ctf0\SimpleMenu\Controllers\Admin\Traits;

trait UserPageOps
{
    protected function getImage($img)
    {
        return $img;
    }

    /**
     * check if model items are the same as the request items.
     * fix spatie role & perm detach then attach model relation.
     *
     * @param [type] $item  [description]
     * @param [type] $input [description]
     *
     * @return [type] [description]
     */
    protected function checkBeforeAssign($item, $input)
    {
        $self = $item->pluck('name')->sort()->values()->all();
        $new  = collect($input)->sort()->values()->all();

        return $self === $new;
    }
}
