<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return view('SimpleMenu::pages.'.config('simpleMenu.framework').'.users.index', compact('users'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles       = Role::get()->pluck('name', 'name');
        $permissions = Permission::get()->pluck('name', 'name');

        return view('SimpleMenu::pages.'.config('simpleMenu.framework').'.users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param \App\Http\Requests\StoreUsersRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'           => 'required',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required',
            'roles'          => 'required',
            'permissions'    => 'required',
        ]);

        $user        = User::create($request->except(['roles', 'permissions']));
        $roles       = $request->input('roles') ?: [];
        $permissions = $request->input('permissions') ?: [];

        $user->assignRole($roles);
        $user->givePermissionTo($permissions);

        return redirect()->route('admin.users.index');
    }

    /**
     * Show the form for editing User.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user        = User::findOrFail($id);
        $roles       = Role::get()->pluck('name', 'name');
        $permissions = Permission::get()->pluck('name', 'name');

        return view('SimpleMenu::pages.'.config('simpleMenu.framework').'.users.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * Update User in storage.
     *
     * @param \App\Http\Requests\UpdateUsersRequest $request
     * @param int                                   $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'        => 'required',
            'email'       => 'required|email|unique:users,email,'.$id,
        ]);

        $user        = User::findOrFail($id);
        $roles       = $request->input('roles') ?: [];
        $permissions = $request->input('permissions') ?: [];

        $user->update($request->except(['roles', 'permissions']));
        $user->syncRoles($roles);
        $user->syncPermissions($permissions);

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove User from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('admin.users.index');
    }
}
