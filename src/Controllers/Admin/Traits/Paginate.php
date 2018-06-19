<?php

namespace ctf0\SimpleMenu\Controllers\Admin\Traits;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * https://gist.github.com/PaulaAguirre/5473cde2a4b066d262bf96cdb231e91a.
 */
trait Paginate
{
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page  = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options ?? [
                'path'     => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );
    }
}
