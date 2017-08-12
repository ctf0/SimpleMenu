<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use ctf0\SimpleMenu\Controllers\BaseController;

class PermissionsController extends BaseController
{
    /**
     * Display a listing of Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = cache('spatie.permission.cache');

        return view("{$this->adminPath}.permissions.index", compact('permissions'));
    }

    /**
     * Show the form for creating new Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("{$this->adminPath}.permissions.create");
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
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create($request->all());

        return redirect()->route($this->crud_prefix . '.permissions.index');
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
        $permission = cache('spatie.permission.cache')->find($id);

        return view("{$this->adminPath}.permissions.edit", compact('permission'));
    }

    /**
     * Update Permission in storage.
     *
     * @param \App\Http\Requests\UpdatePermissionsRequest $request
     * @param int                                         $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name,' . $id,
        ]);

        Permission::find($id)->update($request->all());

        return redirect()->route($this->crud_prefix . '.permissions.index');
    }

    /**
     * Remove Permission from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        Permission::find($id)->delete();

        if ($request->expectsJson()) {
            return response()->json(['done'=>true]);
        }

        return redirect()->route($this->crud_prefix . '.permissions.index');
    }
}
