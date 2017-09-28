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
        $routename = 'required|unique:pages,route_name';

        if ($id) {
            $routename = 'required|unique:pages,route_name,' . $id;
        }

        $customMessages = [
            'template.required_without' => __('validation.required_without', [
                'attribute' => __('SimpleMenu::validation.attributes.template'),
                'values'    => __('SimpleMenu::validation.attributes.action'),
            ]),
            'route_name.required' => __('validation.required', ['attribute' => __('SimpleMenu::validation.attributes.route_name')]),
            'url.required'        => __('validation.required', ['attribute' => __('SimpleMenu::validation.attributes.url')]),
            'title.required'      => __('validation.required', ['attribute' => __('SimpleMenu::validation.attributes.title')]),
         ];

        $validator = Validator::make($request->all(), [
            'template'   => 'required_without:action',
            'route_name' => $routename,
        ], $customMessages);

        // because laravel still pretty fucked up when it comes to showing array input errors
        $validator->after(function ($validator) use ($request, $customMessages) {
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
