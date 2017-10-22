<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use ctf0\SimpleMenu\Controllers\BaseController;
use ctf0\SimpleMenu\Controllers\Admin\Traits\RolePermOps;

class RolesController extends BaseController
{
    use RolePermOps;

    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return view("{$this->adminPath}.roles.index", compact('roles'));
    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get()->pluck('name', 'name');

        return view("{$this->adminPath}.roles.create", compact('permissions'));
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param \App\Http\Requests\StoreRolesRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
        ]);

        $role        = Role::create($request->except('permissions'));
        $permissions = $request->input('permissions') ?: [];

        $role->givePermissionTo($permissions);

        return redirect()->route($this->crud_prefix . '.roles.index');
    }

    /**
     * Show the form for editing Role.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role        = Role::find($id);
        $permissions = Permission::get()->pluck('name', 'name');

        return view("{$this->adminPath}.roles.edit", compact('role', 'permissions'));
    }

    /**
     * Update Role in storage.
     *
     * @param \App\Http\Requests\UpdateRolesRequest $request
     * @param int                                   $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        $role        = Role::find($id);
        $permissions = $request->input('permissions') ?: [];

        $role->update($request->except('permissions'));
        $role->syncPermissions($permissions);

        $this->clearCache();

        return redirect()->route($this->crud_prefix . '.roles.index');
    }

    /**
     * Remove Role from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        Role::find($id)->delete();

        $this->clearCache();

        if ($request->expectsJson()) {
            return response()->json(['done'=>true]);
        }

        return redirect()->route($this->crud_prefix . '.roles.index');
    }
}
