<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use App\Http\Controllers\Controller;
use ctf0\SimpleMenu\Models\Menu;
use ctf0\SimpleMenu\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PagesController extends Controller
{
    /**
     * Display a listing of Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::get();

        return view('SimpleMenu::admin.'.config('simpleMenu.framework').'.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating new Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles       = Role::get()->pluck('name', 'name');
        $permissions = Permission::get()->pluck('name', 'name');
        $menus       = Menu::get()->pluck('name', 'id');

        return view('SimpleMenu::admin.'.config('simpleMenu.framework').'.pages.create', compact('roles', 'permissions', 'menus'));
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

        $page        = Page::create($this->cleanEmptyTrans($request));
        $roles       = $request->input('roles') ?: [];
        $permissions = $request->input('permissions') ?: [];
        $menus       = $request->input('menus') ?: [];

        $page->assignRole($roles);
        $page->givePermissionTo($permissions);
        $page->assignToMenus($menus);

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
        $page        = Page::findOrFail($id);
        $roles       = Role::get()->pluck('name', 'name');
        $permissions = Permission::get()->pluck('name', 'name');
        $menus       = Menu::get()->pluck('name', 'id');

        return view('SimpleMenu::admin.'.config('simpleMenu.framework').'.pages.edit', compact('page', 'roles', 'permissions', 'menus'));
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

        $page        = Page::findOrFail($id);
        $roles       = $request->input('roles') ?: [];
        $permissions = $request->input('permissions') ?: [];
        $menus       = $request->input('menus') ?: [];

        $page->update($this->cleanEmptyTrans($request));
        $page->syncRoles($roles);
        $page->syncPermissions($permissions);
        $page->syncMenus($menus);

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
        Page::findOrFail($id)->delete();
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        DB::table('model_has_permissions')->where('model_id', $id)->delete();

        return redirect()->route('admin.pages.index');
    }

    protected function sT_uP_Validaiton($request, $id = null)
    {
        $routename = 'required|unique:pages,route_name';

        if ($id) {
            $routename = 'required|unique:pages,route_name,'.$id;
        }

        $validator = Validator::make($request->all(), [
            'template'    => 'required_without:action',
            'route_name'  => $routename,
            'roles'       => 'required',
            'permissions' => 'required',
        ]);

        // because laravel is pretty fucked up when it comes to showing array input error
        $validator->after(function ($validator) use ($request) {
            if (!array_filter($request->url)) {
                $validator->errors()->add('url', 'Url is required');
            }

            if (!array_filter($request->title)) {
                $validator->errors()->add('title', 'The Title is required');
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator, $this->buildFailedValidationResponse(
                $request,
                $this->formatValidationErrors($validator)
            ));
        }
    }

    /**
     * [cleanEmptyTrans description].
     *
     * @param [type] $request [description]
     *
     * @return [type] [description]
     */
    protected function cleanEmptyTrans($request)
    {
        $result = $request->except(['roles', 'permissions', 'menus']);
        foreach ($result as $k =>$v) {
            if ($v == null) {
                unset($result[$k]);
            }
            if (is_array($v)) {
                if (empty(array_filter($v))) {
                    unset($result[$k]);
                } else {
                    $result[$k] = array_filter($v);
                }
            }
        }

        return $result;
    }
}
