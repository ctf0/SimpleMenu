<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use Illuminate\Http\Request;
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
        $menus = $this->cache->tags('sm')->get('menus');

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

        $menu = $this->menuModel->create($request->all());

        return redirect()
            ->route($this->crud_prefix . '.menus.index')
            ->with('status', trans('SimpleMenu::messages.model_created'));
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
        $menu = $this->cache->tags('sm')->get('menus')->find($id) ?: abort(404);

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

        $menu = $this->menuModel->findOrFail($id);

        // clear prev records
        $menu->pages()->detach();

        foreach (json_decode($request->saveList) as $item) {
            if (config('simpleMenu.clearPartialyNestedParent')) {
                $this->clearSelfAndNests($item->id);
            } else {
                $this->clearNests($item->id);
            }

            // save page hierarchy
            if ($item->children) {
                $this->saveListToDb($item->children);
            }

            // update the menu root list
            $menu->pages()->attach($item->id, ['order'=>$item->order]);
        }

        // update and trigger events
        $menu->update($request->except('saveList'));
        $menu->touch();

        return back()->with('status', trans('SimpleMenu::messages.model_updated'));
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
        $menu = $this->menuModel->findOrFail($id);
        $menu->pages()->detach();
        $menu->delete();

        if ($request->expectsJson()) {
            return response()->json(['done'=>true]);
        }

        return redirect()
            ->route($this->crud_prefix . '.menus.index')
            ->with('status', trans('SimpleMenu::messages.model_deleted'));
    }

    public function destroyMulti(Request $request)
    {
        $ids = explode(',', $request->ids);

        foreach ($ids as $one) {
            $menu = $this->menuModel->findOrFail($one);
            $menu->pages()->detach();
            $menu->delete();
        }

        return redirect()
            ->route($this->crud_prefix . '.menus.index')
            ->with('status', trans('SimpleMenu::messages.models_deleted'));
    }
}
