<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use App\Http\Controllers\Controller;
use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenusController extends Controller
{
    /**
     * Display a listing of Menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::all();

        return view('SimpleMenu::admin.'.config('simpleMenu.framework').'.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating new Menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('SimpleMenu::admin.'.config('simpleMenu.framework').'.menus.create');
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

        Menu::create($request->all());

        return redirect()->route('admin.menus.index');
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
        $menu  = Menu::findOrFail($id);

        return view('SimpleMenu::admin.'.config('simpleMenu.framework').'.menus.edit', compact('menu'));
    }

    /**
     * Update Menu in storage.
     *
     * @param \App\Http\Requests\UpdateMenusRequest $request
     * @param int                                   $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:menus,name,'.$id,
        ]);

        foreach (json_decode($request->saveList) as $item) {
            DB::table('menu_page')->where('page_id', $item->id)->update(['order'=>$item->order]);
        }

        Menu::findOrFail($id)->update($request->except('saveList'));

        // todo
        // page nest list

        return back();
    }

    /**
     * Remove Menu from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Menu::findOrFail($id)->delete();

        return redirect()->route('admin.menus.index');
    }

    /**
     * remove page from menu with ajax.
     *
     * @param mixed $id
     */
    public function removePage($id, Request $request)
    {
        if (Menu::find($id)->pages()->detach($request->page_id)) {
            Menu::find($id)->touch();

            return response()->json(['done'=>true]);
        }
    }

    /**
     * get all menu pages for sorting with vuejs.
     */
    public function getPages(Menu $id)
    {
        $pages = $id->pages()->orderBy('pivot_order', 'asc')->where('url->'.app()->getLocale(), '!=', '')->get();

        return response()->json(['data' => $pages]);
    }
}
