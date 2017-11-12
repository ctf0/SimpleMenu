<?php

namespace ctf0\SimpleMenu\Controllers\Admin\Traits;

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
        $customMessages = [
            'template.required_without' => trans('validation.required_without', [
                'attribute' => trans('SimpleMenu::messages.template'),
                'values'    => trans('SimpleMenu::messages.action'),
            ]),
            'route_name.required' => trans('validation.required', ['attribute' => trans('SimpleMenu::messages.route_name')]),
            'url.required'        => trans('validation.required', ['attribute' => trans('SimpleMenu::messages.url')]),
            'url.unique'          => trans('validation.unique', ['attribute' => trans('SimpleMenu::messages.url')]),
            'title.required'      => trans('validation.required', ['attribute' => trans('SimpleMenu::messages.title')]),
            'title.unique'        => trans('validation.unique', ['attribute' => trans('SimpleMenu::messages.title')]),
        ];

        // main
        $validator = Validator::make($request->all(), [
            'template'   => 'required_without:action',
            'route_name' => 'required|unique:pages,route_name,' . $id ?: '',
            'url.*'      => 'unique_translation:pages,url,' . $id ?: '',
            'title.*'    => 'unique_translation:pages,title,' . $id ?: '',
        ], $customMessages);

        // extra
        $validator->after(function ($validator) use ($request, $customMessages, $id) {
            // url
            if (!array_filter($request->url)) {
                $validator->errors()->add('url', $customMessages['url.required']);
            }

            // title
            if (!array_filter($request->title)) {
                $validator->errors()->add('title', $customMessages['title.required']);
            }
        });

        if ($validator->fails()) {
            throw (new ValidationException($validator))
                        ->errorBag('default')
                        ->redirectTo(url()->previous());
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
        $result = $request->except(['roles', 'permissions', 'menus', 'cover']);

        foreach ($result as $k => $v) {
            if (is_array($v)) {
                if (!array_filter($v)) {
                    $result[$k] = null;
                } else {
                    $result[$k] = array_filter($v);
                }
            }
        }

        return $result;
    }
}
