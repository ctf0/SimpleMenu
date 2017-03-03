<?php

namespace App\Http\Controllers;

use Route;

class PageController extends Controller
{
    /**
     * register routes for menu pages.
     *
     * @return [type] [description]
     */
    public function createRoutes()
    {
        if ($pages = cache('pages')) {
            foreach ($pages as $page) {
                $this->genPage($page);
            }
        }
    }

    protected function genPage($page)
    {
        $name = slugfy($page->getTranslation('title', 'en'));
        $route = slugfy($page->prefix).'/'.slugfy($page->title);
        $body = $page->body;
        $desc = trimfy($body);

        $action = $page->action;
        $roles = 'role:'.implode(',', $page->roles()->pluck('name')->toArray());
        $permissions = 'perm:'.implode(',', $page->permissions()->pluck('name')->toArray());

        if ($action) {
            Route::get($route, $action)->name($name)->middleware([$roles, $permissions]);
        } else {
            Route::get($route, function () use ($desc, $body, $page) {
                return view("pages.{$page->template}")->with([
                    'title'=> $page->title,
                    'body' => $body,
                    'desc' => $desc,
                ]);
            })->name($name)->middleware([$roles, $permissions]);
        }
    }
}
