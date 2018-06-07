<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use Illuminate\Http\Request;
use ctf0\SimpleMenu\Facade\SimpleMenu;
use ctf0\SimpleMenu\Controllers\BaseController;
use ctf0\SimpleMenu\Controllers\Admin\Traits\PageOps;
use ctf0\SimpleMenu\Controllers\Admin\Traits\UserPageOps;

class PagesController extends BaseController
{
    use PageOps, UserPageOps;

    /**
     * Display a listing of Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = $this->cache->tags('sm')->get('pages');

        return view("{$this->adminPath}.pages.index", compact('pages'));
    }

    /**
     * Show the form for creating new Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locales     = SimpleMenu::AppLocales();
        $roles       = $this->roleModel->pluck('name', 'name');
        $permissions = $this->permissionModel->pluck('name', 'name');
        $menus       = $this->cache->tags('sm')->get('menus')->pluck('name', 'id');
        $templates   = array_unique($this->cache->tags('sm')->get('pages')->pluck('template')->filter()->all());

        return view("{$this->adminPath}.pages.create", compact('locales', 'roles', 'permissions', 'menus', 'templates'));
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

        $img         = $this->getImage($request->cover);
        $page        = $this->pageModel->create(array_merge(['cover'=>$img], $this->cleanEmptyTranslations($request)));
        $roles       = $request->input('roles') ?: [];
        $permissions = $request->input('permissions') ?: [];
        $menus       = $request->input('menus') ?: [];

        $page->assignRole($roles);
        $page->givePermissionTo($permissions);
        $page->assignToMenus($menus);

        return redirect()
            ->route($this->crud_prefix . '.pages.index')
            ->with('status', trans('SimpleMenu::messages.model_created'));
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
        $locales     = SimpleMenu::AppLocales();
        $roles       = $this->roleModel->pluck('name', 'name');
        $permissions = $this->permissionModel->pluck('name', 'name');
        $page        = $this->cache->tags('sm')->get('pages')->find($id) ?: abort(404);
        $menus       = $this->cache->tags('sm')->get('menus')->pluck('name', 'id');
        $templates   = array_unique($this->cache->tags('sm')->get('pages')->pluck('template')->filter()->all());

        $controllerFile = $page->action ? $this->actionFileContent($page->action, 'get') : null;

        return view("{$this->adminPath}.pages.edit", compact('locales', 'roles', 'permissions', 'page', 'menus', 'templates', 'controllerFile'));
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

        $img         = $this->getImage($request->cover);
        $page        = $this->getItem($id);
        $roles       = $request->input('roles') ?: [];
        $permissions = $request->input('permissions') ?: [];
        $menus       = $request->input('menus') ?: [];

        $page->update(array_merge(['cover'=>$img], $this->cleanEmptyTranslations($request)));
        $page->syncRoles($roles);
        $page->syncPermissions($permissions);
        $page->syncMenus($menus);

        if (!is_null($request->controllerFile)) {
            $this->actionFileContent($request->action, 'update', $request->controllerFile);
        }

        return back()->with('status', trans('SimpleMenu::messages.model_updated'));
    }

    /**
     * Remove Page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $this->pageModel->destroy($id);

        if ($request->expectsJson()) {
            return response()->json(['done'=>true]);
        }

        return redirect()
            ->route($this->crud_prefix . '.pages.index')
            ->with('status', trans('SimpleMenu::messages.model_deleted'));
    }

    public function destroyMulti(Request $request)
    {
        $ids = explode(',', $request->ids);

        $this->pageModel->destroy($ids);

        return redirect()
            ->route($this->crud_prefix . '.pages.index')
            ->with('status', trans('SimpleMenu::messages.models_deleted'));
    }

    /**
     * restore model.
     *
     * @param [type] $id [description]
     *
     * @return [type] [description]
     */
    public function restore($id)
    {
        $this->getItem($id)->restore();

        return redirect()
            ->route($this->crud_prefix . '.pages.index')
            ->with('status', trans('SimpleMenu::messages.model_updated'));
    }

    /**
     * Remove Page Permanently.
     *
     * @param [type] $id [description]
     *
     * @return [type] [description]
     */
    public function forceDelete($id)
    {
        $this->getItem($id)->forceDelete();

        return redirect()
            ->route($this->crud_prefix . '.pages.index')
            ->with('status', trans('SimpleMenu::messages.model_deleted_perm'));
    }

    /**
     * helper.
     *
     * @param [type] $id [description]
     *
     * @return [type] [description]
     */
    protected function getItem($id)
    {
        return $this->pageModel->withTrashed()->findOrFail($id);
    }
}
