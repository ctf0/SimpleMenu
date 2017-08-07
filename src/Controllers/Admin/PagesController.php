<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use ctf0\SimpleMenu\Controllers\Admin\Traits\PageOps;
use ctf0\SimpleMenu\Controllers\BaseController;
use ctf0\SimpleMenu\Models\Page;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class PagesController extends BaseController
{
    use PageOps;

    /**
     * Display a listing of Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = cache('sm-pages');

        return view("{$this->adminPath}.pages.index", compact('pages'));
    }

    /**
     * Show the form for creating new Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles       = Role::get()->pluck('name', 'name');
        $permissions = cache('spatie.permission.cache')->pluck('name', 'name');
        $menus       = cache('sm-menus')->pluck('name', 'id');

        return view("{$this->adminPath}.pages.create", compact('roles', 'permissions', 'menus'));
    }

    /**
     * Store a newly created Page in storage.
     *
     * @param \App\Http\Requests\StorePagesRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->sT_uP_Validaiton($request);

        $page        = Page::create($this->cleanEmptyTranslations($request));
        $roles       = $request->input('roles') ?: [];
        $permissions = $request->input('permissions') ?: [];
        $menus       = $request->input('menus') ?: [];

        $page->assignRole($roles);
        $page->givePermissionTo($permissions);
        $page->assignToMenus($menus);

        $this->clearCache();

        return redirect()->route('admin.pages.index');
    }

    /**
     * Show the form for editing Page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles       = Role::get()->pluck('name', 'name');
        $permissions = cache('spatie.permission.cache')->pluck('name', 'name');
        $page        = cache('sm-pages')->find($id);
        $menus       = cache('sm-menus')->pluck('name', 'id');

        return view("{$this->adminPath}.pages.edit", compact('roles', 'permissions', 'page', 'menus'));
    }

    /**
     * Update Page in storage.
     *
     * @param \App\Http\Requests\UpdatePagesRequest $request
     * @param int                                   $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $this->sT_uP_Validaiton($request, $id);

        $page        = Page::find($id);
        $roles       = $request->input('roles') ?: [];
        $permissions = $request->input('permissions') ?: [];
        $menus       = $request->input('menus') ?: [];

        $page->update($this->cleanEmptyTranslations($request));
        $page->syncRoles($roles);
        $page->syncPermissions($permissions);
        $page->syncMenus($menus);

        $this->clearCache();

        return redirect()->route('admin.pages.index');
    }

    /**
     * Remove Page from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Page::find($id)->delete();

        $this->clearCache();

        return redirect()->route('admin.pages.index');
    }
}
