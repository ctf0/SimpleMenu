<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return view('SimpleMenu::admin.'.config('simpleMenu.framework').'.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get()->pluck('name', 'name');

        return view('SimpleMenu::admin.'.config('simpleMenu.framework').'.roles.create', compact('permissions'));
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
            'name' => 'required',
        ]);

        $role        = Role::create($request->except('permissions'));
        $permissions = $request->input('permissions') ?: [];

        $role->givePermissionTo($permissions);

        return redirect()->route('admin.roles.index');
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
        $role        = Role::findOrFail($id);
        $permissions = Permission::get()->pluck('name', 'name');

        return view('SimpleMenu::admin.'.config('simpleMenu.framework').'.roles.edit', compact('role', 'permissions'));
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
            'name' => 'required',
        ]);

        $role        = Role::findOrFail($id);
        $permissions = $request->input('permissions') ?: [];

        $role->update($request->except('permissions'));
        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index');
    }

    /**
     * Remove Role from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::findOrFail($id)->delete();

        return redirect()->route('admin.roles.index');
    }
}
