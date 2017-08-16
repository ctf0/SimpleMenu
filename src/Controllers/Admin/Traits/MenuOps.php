<?php

namespace ctf0\SimpleMenu\Controllers\Admin\Traits;

use Illuminate\Http\Request;
use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;

trait MenuOps
{
    /**
     * get all menu pages for ajax.
     *
     * @param Menu $id [description]
     *
     * @return [type] [description]
     */
    public function getMenuPages($id)
    {
        $pages = cache('sm-menus')->find($id)->pages()->orderBy('pivot_order', 'asc')->get()->each(function ($item) {
            $item['from'] = 'pages';
        });

        $allPages = cache('sm-pages')->diff($pages)->each(function ($item) {
            $item['from'] = 'allPages';
        });

        return response()->json(compact('pages', 'allPages'));
    }

    /**
     * remove page from menu with ajax.
     *
     * @param [type]  $id      [description]
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function removePage($id, Request $request)
    {
        // remove page from menu
        $menu = Menu::find($id);
        $menu->pages()->detach($request->page_id);
        $menu->touch();

        // clear page nesting
        if (config('simpleMenu.clearRootDescendants')) {
            $this->clearSelfAndNests($request->page_id);
        }

        return response()->json(['done'=>true]);
    }

    /**
     * remove nested child with ajax.
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function removeChild(Request $request)
    {
        if (config('simpleMenu.clearNestDescendants')) {
            $this->clearSelfAndNests($request->child_id);
        } else {
            $page = $this->findPage($request->child_id);
            $page->makeRoot();
            $page->touch();
        }

        return response()->json(['done'=>true]);
    }

    /**
     * save page hierarchy to db.
     *
     * @param [type] $list [description]
     *
     * @return [type] [description]
     */
    protected function saveListToDb($list)
    {
        foreach ($list as $one) {
            $child  = $this->findPage($one->id);
            $parent = $this->findPage($one->parent_id);

            if ($child == $parent) {
                return;
            }

            $child->makeChildOf($parent);

            $child->touch();
            $parent->touch();

            if ($one->children) {
                $this->saveListToDb($one->children);
            }
        }
    }

    /**
     * helpers.
     *
     * @param mixed $id
     *
     * @return [type] [description]
     */
    protected function clearNests($id)
    {
        return $this->findPage($id)->destroyDescendants();
    }

    protected function clearSelfAndNests($id)
    {
        $page = $this->findPage($id);

        return $page->clearSelfAndDescendants();
    }

    protected function findPage($id)
    {
        return Page::find($id);
    }
}
