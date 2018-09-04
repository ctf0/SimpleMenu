<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use Illuminate\Http\Request;
use ctf0\SimpleMenu\Controllers\BaseController;
use ctf0\SimpleMenu\Controllers\Admin\Traits\RolePermOps;

class PermissionsController extends BaseController
{
    use RolePermOps;

    /**
     * Display a listing of Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = $this->permissionModel->get();

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

        $this->permissionModel->create($request->all());

        return redirect()
            ->route($this->crud_prefix . '.permissions.index')
            ->with('status', trans('SimpleMenu::messages.model_created'));
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
        $permission = $this->permissionModel->findOrFail($id);

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

        $this->permissionModel->findOrFail($id)->update($request->all());
        $this->permissionModel->touch();

        $this->clearCache();

        return back()->with('status', trans('SimpleMenu::messages.model_updated'));
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
        $this->permissionModel->destroy($id);

        $this->clearCache();

        if ($request->expectsJson()) {
            return response()->json(['done'=>true]);
        }

        return redirect()
            ->route($this->crud_prefix . '.permissions.index')
            ->with('status', trans('SimpleMenu::messages.model_deleted'));
    }

    public function destroyMulti(Request $request)
    {
        $ids = explode(',', $request->ids);

        $this->permissionModel->destroy($ids);

        return redirect()
            ->route($this->crud_prefix . '.permissions.index')
            ->with('status', trans('SimpleMenu::messages.models_deleted'));
    }
}
