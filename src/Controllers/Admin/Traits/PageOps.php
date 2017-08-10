<?php

namespace ctf0\SimpleMenu\Controllers\Admin\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait PageOps
{
    /**
     * validation.
     *
     * @param [type] $request [description]
     * @param [type] $id      [description]
     *
     * @return [type] [description]
     */
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

        // because laravel still pretty fucked up when it comes to showing array input errors
        $validator->after(function ($validator) use ($request) {
            // url
            if (!array_filter($request->url)) {
                $validator->errors()->add('url', 'Url is required');
            }

            // title
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
     * make sure we only have fields with values.
     *
     * @param [type] $request [description]
     *
     * @return [type] [description]
     */
    protected function cleanEmptyTranslations($request)
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

    protected function clearCache()
    {
        return Cache::forget('sm-pages');
    }
}
