<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use Illuminate\Http\Request;
use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
use Illuminate\Support\Facades\Cache;
use ctf0\SimpleMenu\Controllers\BaseController;
use ctf0\SimpleMenu\Controllers\Admin\Traits\MenuOps;

class MenusController extends BaseController
{
    use MenuOps;

    /**
     * Display a listing of Menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = cache('sm-menus');

        return view("{$this->adminPath}.menus.index", compact('menus'));
    }

    /**
     * Show the form for creating new Menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("{$this->adminPath}.menus.create");
    }

    /**
     * Store a newly created Menu in storage.
     *
     * @param \App\Http\Requests\StoreMenusRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:menus,name',
        ]);

        $menu = Menu::create($request->all());
        $menu->cleanData();

        return redirect()->route($this->crud_prefix . '.menus.index');
    }

    /**
     * Show the form for editing Menu.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = cache('sm-menus')->find($id);

        return view("{$this->adminPath}.menus.edit", compact('menu'));
    }

    /**
     * Update Menu in storage.
     *
     * @param \App\Http\Requests\UpdateMenusRequest $request
     * @param int                                   $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:menus,name,' . $id,
        ]);

        $menu = Menu::find($id);

        // clear prev records
        $menu->pages()->detach();

        foreach (json_decode($request->saveList) as $item) {
            // make sure page is not included under any other pages
            $this->clearSelfAndNests($item->id);

            // save page hierarchy
            if ($item->children) {
                $this->saveListToDb($item->children);
            }

            // update the menu root list
            $menu->pages()->attach($item->id, ['order'=>$item->order]);
        }

        // update and trigger events
        $menu->update($request->except('saveList'));
        $menu->cleanData();

        return back();
    }

    /**
     * Remove Menu from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $menu = Menu::find($id);
        $menu->pages()->detach();
        $menu->delete();
        $menu->cleanData();

        if ($request->expectsJson()) {
            return response()->json(['done'=>true]);
        }

        return redirect()->route($this->crud_prefix . '.menus.index');
    }
}
