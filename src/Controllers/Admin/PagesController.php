<?php

namespace ctf0\SimpleMenu\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $pages = Page::where('title->'.app()->getLocale(), '!=', '')->get();

        return view('SimpleMenu::pages.'.config('simpleMenu.framework').'.pages.index', compact('pages'));
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

        return view('SimpleMenu::pages.'.config('simpleMenu.framework').'.pages.create', compact('roles', 'permissions'));
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
        $this->storeValidation($request);

        $page        = Page::create($this->cleanEmptyTrans($request));
        $roles       = $request->input('roles') ?: [];
        $permissions = $request->input('permissions') ?: [];

        $page->assignRole($roles);
        $page->givePermissionTo($permissions);

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

        return view('SimpleMenu::pages.'.config('simpleMenu.framework').'.pages.edit', compact('page', 'roles', 'permissions'));
    }

    /**
     * Update Page in storage.
     *
     * @param \App\Http\Requests\UpdatePagesRequest $request
     * @param int                                   $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->updateValidation($request, $id);

        $page        = Page::findOrFail($id);
        $roles       = $request->input('roles') ?: [];
        $permissions = $request->input('permissions') ?: [];

        $page->update($this->cleanEmptyTrans($request));
        $page->syncRoles($roles);
        $page->syncPermissions($permissions);

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

    /**
     * [storeValidation description].
     *
     * @param [type] $request [description]
     *
     * @return [type] [description]
     */
    protected function storeValidation($request)
    {
        $validator = Validator::make($request->all(), [
            'route_name'  => 'required|unique:pages,route_name',
            'template'    => 'required_without:action',
            'roles'       => 'required',
            'permissions' => 'required',
        ]);

        // because laravel is pretty fucked up when it comes to showing input array error
        $validator->after(function ($validator) use ($request) {
            if (!array_filter($request->url)) {
                $validator->errors()->add('url', 'The Url is required');
            }

            if (!array_filter($request->title)) {
                $validator->errors()->add('title', 'The Title is required');
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator, $this->buildFailedValidationResponse(
                $request, $this->formatValidationErrors($validator)
            ));
        }
    }

    /**
     * [updateValidation description].
     *
     * @param [type] $request [description]
     * @param [type] $id      [description]
     *
     * @return [type] [description]
     */
    protected function updateValidation($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'route_name'   => 'required|unique:pages,route_name,'.$id,
            'template'     => 'required_without:action',
            'roles'        => 'required',
            'permissions'  => 'required',
        ]);

        // because laravel is pretty fucked up when it comes to showing input array error
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
                $request, $this->formatValidationErrors($validator)
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
        $result = $request->except(['roles', 'permissions']);
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
