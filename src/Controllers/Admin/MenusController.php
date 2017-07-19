<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use App\Http\Controllers\Controller;
use ctf0\SimpleMenu\Models\Menu;
use Illuminate\Http\Request;

class MenusController extends Controller
{
    /**
     * Display a listing of Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::all();

        return view('SimpleMenu::pages.'.config('simpleMenu.framework').'.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating new Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('SimpleMenu::pages.'.config('simpleMenu.framework').'.menus.create');
    }

    /**
     * Store a newly created Permission in storage.
     *
     * @param \App\Http\Requests\StoreMenusRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        Menu::create($request->all());

        return redirect()->route('admin.menus.index');
    }

    /**
     * Show the form for editing Permission.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);

        return view('SimpleMenu::pages.'.config('simpleMenu.framework').'.menus.edit', compact('menu'));
    }

    /**
     * Update Permission in storage.
     *
     * @param \App\Http\Requests\UpdateMenusRequest $request
     * @param int                                   $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        Menu::findOrFail($id)->update($request->all());

        return redirect()->route('admin.menus.index');
    }

    /**
     * Remove Permission from storage.
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
}
