<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    /**
     * Display a listing of Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();

        return view('SimpleMenu::pages.'.config('simpleMenu.framework').'.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating new Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('SimpleMenu::pages.'.config('simpleMenu.framework').'.permissions.create');
    }

    /**
     * Store a newly created Permission in storage.
     *
     * @param \App\Http\Requests\StorePermissionsRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        Permission::create($request->all());

        return redirect()->route('admin.permissions.index');
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
        $permission = Permission::findOrFail($id);

        return view('SimpleMenu::pages.'.config('simpleMenu.framework').'.permissions.edit', compact('permission'));
    }

    /**
     * Update Permission in storage.
     *
     * @param \App\Http\Requests\UpdatePermissionsRequest $request
     * @param int                                         $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        Permission::findOrFail($id)->update($request->all());

        return redirect()->route('admin.permissions.index');
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
        Permission::findOrFail($id)->delete();

        return redirect()->route('admin.permissions.index');
    }
}
